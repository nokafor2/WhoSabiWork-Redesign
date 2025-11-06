<template>
    <div>
        <!-- Pull to refresh indicator -->
        <div 
            v-if="isPulling || isRefreshing" 
            class="pull-refresh-indicator"
            :style="{ 
                transform: `translateY(${isPulling ? pullDistance : 0}px)`,
                opacity: isPulling ? (pullProgress / 100) : 1
            }"
        >
            <div class="text-center py-3">
                <i 
                    class="fas fa-arrow-down refresh-icon" 
                    :class="{ 
                        'fa-spin': isRefreshing,
                        'fa-arrow-down': !isRefreshing && pullProgress < 100,
                        'fa-sync-alt': isRefreshing || pullProgress >= 100
                    }"
                    :style="{ transform: `rotate(${pullProgress * 1.8}deg)` }"
                ></i>
                <p class="mb-0 mt-2 text-muted small">
                    <span v-if="isRefreshing">Refreshing...</span>
                    <span v-else-if="pullProgress >= 100">Release to refresh</span>
                    <span v-else>Pull down to refresh</span>
                </p>
            </div>
        </div>

        <!-- Manual refresh button (floating) - Hidden for now -->
        <!-- <button 
            @click="triggerManualRefresh" 
            class="btn btn-primary btn-floating-refresh"
            :class="{ 'refreshing': isRefreshing }"
            :disabled="isRefreshing"
            :title="isRefreshing ? 'Refreshing...' : 'Refresh feed'"
        >
            <i class="fas fa-sync-alt" :class="{ 'fa-spin': isRefreshing }"></i>
        </button> -->

        <div class="row p-3 justify-content-evenly">
            <ad-card v-for="photoData in photoFeedRecords.data" :key="photoData.id"
                :photoData=photoData
            ></ad-card>
        </div>

        <!-- Loading Indicator -->
        <div v-if="isLoading" class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading more photos...</p>
        </div>

        <!-- End of Feed Message -->
        <div v-if="!hasMorePages && photoFeedRecords.data && photoFeedRecords.data.length > 0" class="text-center py-4">
            <p class="text-muted">
                <i class="fas fa-check-circle me-2"></i>
                You've reached the end of the photo feed
            </p>
        </div>

        <!-- Empty State -->
        <div v-if="!isLoading && photoFeedRecords.data && photoFeedRecords.data.length === 0" class="text-center py-5">
            <i class="fas fa-images fa-3x text-muted mb-3"></i>
            <p class="text-muted">No photos available yet</p>
        </div>
    </div>
</template>


<script>
    import AdCard from "../../components/UI/AdCard.vue";
    import { router } from '@inertiajs/vue3';

    export default {
        components: { AdCard },
        props: ['photoFeedData'],
        data() {
            return {
                isLoading: false,
                currentPage: 1,
                refreshInterval: null,
                lastRefreshTime: null,
                isRefreshing: false,
                pullStartY: 0,
                pullCurrentY: 0,
                isPulling: false,
                pullThreshold: 80,
            }
        },
        methods: {
            handleScroll() {
                // Check if user has scrolled to bottom
                const bottomOfWindow = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100;
                
                if (bottomOfWindow && !this.isLoading && this.hasMorePages) {
                    this.loadMorePhotos();
                }
            },
            async loadMorePhotos() {
                if (this.isLoading || !this.hasMorePages) return;
                
                this.isLoading = true;
                const nextPage = this.currentPage + 1;
                
                try {
                    // Use Inertia router to fetch next page
                    router.get(
                        route('home.photoFeeds', { page: nextPage }),
                        {},
                        {
                            preserveState: true,
                            preserveScroll: true,
                            only: ['photoFeedData'],
                            onSuccess: (page) => {
                                // Append new data to existing photos
                                this.$store.dispatch('appendPhotoFeedData', { value: page.props.photoFeedData });
                                this.currentPage = nextPage;
                                this.isLoading = false;
                            },
                            onError: (errors) => {
                                console.error('Error loading more photos:', errors);
                                this.isLoading = false;
                            }
                        }
                    );
                } catch (error) {
                    console.error('Error loading more photos:', error);
                    this.isLoading = false;
                }
            },
            refreshFeed() {
                console.log('Refreshing photo feed...');
                this.isRefreshing = true;
                
                try {
                    // Fetch the first page to get the latest photos
                    router.get(
                        route('home.photoFeeds', { page: 1 }),
                        {},
                        {
                            preserveState: true,
                            preserveScroll: true,
                            only: ['photoFeedData'],
                            onSuccess: (page) => {
                                // Update with fresh data from page 1
                                this.$store.dispatch('updatePhotoFeedData', { value: page.props.photoFeedData });
                                this.currentPage = 1;
                                this.lastRefreshTime = new Date();
                                this.isRefreshing = false;
                                console.log('Photo feed refreshed successfully at', this.lastRefreshTime.toLocaleTimeString());
                            },
                            onError: (errors) => {
                                console.error('Error refreshing photo feed:', errors);
                                this.isRefreshing = false;
                            }
                        }
                    );
                } catch (error) {
                    console.error('Error refreshing photo feed:', error);
                    this.isRefreshing = false;
                }
            },
            startAutoRefresh() {
                // Refresh every 5 minutes (300000 milliseconds)
                this.refreshInterval = setInterval(() => {
                    this.refreshFeed();
                }, 300000); // 5 minutes
                
                console.log('Auto-refresh started: Feed will refresh every 5 minutes');
            },
            stopAutoRefresh() {
                if (this.refreshInterval) {
                    clearInterval(this.refreshInterval);
                    this.refreshInterval = null;
                    console.log('Auto-refresh stopped');
                }
            },
            // Pull to refresh methods
            handleTouchStart(e) {
                if (window.scrollY === 0 && !this.isRefreshing) {
                    this.pullStartY = e.touches[0].clientY;
                    this.isPulling = true;
                }
            },
            handleTouchMove(e) {
                if (!this.isPulling || window.scrollY > 0) {
                    this.isPulling = false;
                    return;
                }
                
                this.pullCurrentY = e.touches[0].clientY - this.pullStartY;
                
                // Only allow pulling down (positive values)
                if (this.pullCurrentY > 0) {
                    // Prevent default scroll behavior when pulling
                    if (this.pullCurrentY > 10) {
                        e.preventDefault();
                    }
                } else {
                    this.pullCurrentY = 0;
                }
            },
            handleTouchEnd() {
                if (this.isPulling && this.pullCurrentY >= this.pullThreshold) {
                    // Trigger refresh
                    this.triggerManualRefresh();
                }
                
                // Reset pull state
                this.isPulling = false;
                this.pullCurrentY = 0;
                this.pullStartY = 0;
            },
            triggerManualRefresh() {
                if (this.isRefreshing) return;
                
                console.log('Manual refresh triggered by pull gesture');
                this.refreshFeed();
            }
        },
        computed: {
            photoFeedRecords() {
                return this.$store.getters.getPhotoFeedData;
            },
            hasMorePages() {
                if (!this.photoFeedRecords || !this.photoFeedRecords.current_page) return false;
                return this.photoFeedRecords.current_page < this.photoFeedRecords.last_page;
            },
            formatRefreshTime() {
                if (!this.lastRefreshTime) return '';
                return this.lastRefreshTime.toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    second: '2-digit'
                });
            },
            pullDistance() {
                return Math.min(this.pullCurrentY, this.pullThreshold);
            },
            pullProgress() {
                return Math.min((this.pullCurrentY / this.pullThreshold) * 100, 100);
            }
        },
        mounted() {
            this.$store.dispatch('updatePhotoFeedData', { value: this.photoFeedData });
            this.currentPage = this.photoFeedData.current_page || 1;
            this.lastRefreshTime = new Date();
            
            // Add scroll event listener
            window.addEventListener('scroll', this.handleScroll);
            
            // Add touch event listeners for pull-to-refresh
            document.addEventListener('touchstart', this.handleTouchStart, { passive: false });
            document.addEventListener('touchmove', this.handleTouchMove, { passive: false });
            document.addEventListener('touchend', this.handleTouchEnd);
            
            // Start auto-refresh timer
            this.startAutoRefresh();
        },
        beforeUnmount() {
            // Remove scroll event listener when component is destroyed
            window.removeEventListener('scroll', this.handleScroll);
            
            // Remove touch event listeners
            document.removeEventListener('touchstart', this.handleTouchStart);
            document.removeEventListener('touchmove', this.handleTouchMove);
            document.removeEventListener('touchend', this.handleTouchEnd);
            
            // Stop auto-refresh timer
            this.stopAutoRefresh();
        }
    }
</script>

<style scoped>
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Pull to refresh indicator */
.pull-refresh-indicator {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1050;
    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-out, opacity 0.2s ease;
    border-bottom: 1px solid #dee2e6;
}

.refresh-icon {
    font-size: 1.5rem;
    color: #007bff;
    transition: transform 0.3s ease, color 0.2s ease;
}

.fa-spin {
    animation: fa-spin 1s infinite linear;
}

@keyframes fa-spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* Refresh indicator styling */
.bg-light.border-bottom {
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.fa-sync-alt {
    transition: all 0.3s ease;
}

/* Prevent text selection during pull */
.pull-refresh-indicator * {
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
}

/* Floating refresh button */
.btn-floating-refresh {
    position: fixed;
    top: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1040;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
    transition: all 0.3s ease;
    border: none;
}

.btn-floating-refresh:hover {
    transform: scale(1.1) rotate(180deg);
    box-shadow: 0 6px 16px rgba(0, 123, 255, 0.6);
}

.btn-floating-refresh:active {
    transform: scale(0.95);
}

.btn-floating-refresh.refreshing {
    cursor: not-allowed;
    opacity: 0.8;
}

.btn-floating-refresh.refreshing:hover {
    transform: scale(1);
}

.btn-floating-refresh i {
    font-size: 1.2rem;
}

/* Responsive adjustments for floating button */
@media (max-width: 768px) {
    .btn-floating-refresh {
        width: 45px;
        height: 45px;
        top: 15px;
        right: 15px;
    }
    
    .btn-floating-refresh i {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .btn-floating-refresh {
        width: 40px;
        height: 40px;
        top: 10px;
        right: 10px;
    }
    
    .btn-floating-refresh i {
        font-size: 0.9rem;
    }
}
</style>
