# FeedbackModal Component

A reusable, customizable feedback modal component that can be used throughout the application for displaying errors, notifications, and confirmations.

## Features

- ✅ Teleported to body (appears on top of all content)
- ✅ Beautiful, modern design with animations
- ✅ Customizable title, message, and buttons
- ✅ Optional action button (e.g., for login redirect)
- ✅ Responsive design (mobile-friendly)
- ✅ Prevents body scrolling when open
- ✅ Click outside to close (optional)
- ✅ Smooth transitions and animations

## Usage

### 1. Import the component

```javascript
import FeedbackModal from '@/components/UI/FeedbackModal.vue';

export default {
    components: { FeedbackModal },
    // ...
}
```

### 2. Add to template

```vue
<template>
    <FeedbackModal
        :visible="feedbackModal.visible"
        :title="feedbackModal.title"
        :message="feedbackModal.message"
        :show-action-button="feedbackModal.showActionButton"
        :action-button-text="feedbackModal.actionButtonText"
        :action-icon="feedbackModal.actionIcon"
        @close="closeFeedbackModal"
        @action="handleFeedbackAction">
    </FeedbackModal>
    
    <!-- Your other content -->
</template>
```

### 3. Add data properties

```javascript
data() {
    return {
        feedbackModal: {
            visible: false,
            title: 'Notification',
            message: '',
            showActionButton: false,
            actionButtonText: 'OK',
            actionIcon: 'fas fa-check',
        }
    }
}
```

### 4. Add methods

```javascript
methods: {
    showFeedbackModal(title, message, showAction = false) {
        this.feedbackModal.title = title;
        this.feedbackModal.message = message;
        this.feedbackModal.showActionButton = showAction;
        this.feedbackModal.visible = true;
    },
    closeFeedbackModal() {
        this.feedbackModal.visible = false;
    },
    handleFeedbackAction() {
        // Custom action (e.g., redirect to login)
        window.location.href = route('users.signin');
    }
}
```

### 5. Show the modal

```javascript
// Simple feedback message
this.showFeedbackModal('Error', 'Something went wrong');

// Feedback with action button
this.showFeedbackModal(
    'Authentication Required',
    'You must be logged in to continue.',
    true
);
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | String | `'Notification'` | Modal title |
| `message` | String | **(required)** | Feedback message to display |
| `visible` | Boolean | `false` | Controls modal visibility |
| `showActionButton` | Boolean | `false` | Show/hide action button |
| `actionButtonText` | String | `'OK'` | Text for action button |
| `actionIcon` | String | `'fas fa-check'` | Font Awesome icon for action button |
| `closeOnOverlayClick` | Boolean | `true` | Allow closing by clicking overlay |

## Events

| Event | Description |
|-------|-------------|
| `@close` | Emitted when modal is closed |
| `@action` | Emitted when action button is clicked |

## Examples

### Basic Feedback Message

```vue
<FeedbackModal
    :visible="showFeedback"
    title="Success!"
    message="Your changes have been saved successfully."
    @close="showFeedback = false">
</FeedbackModal>
```

### Error Message

```vue
<FeedbackModal
    :visible="showError"
    title="Oops!"
    message="Something went wrong. Please try again."
    @close="showError = false">
</FeedbackModal>
```

### Authentication Error with Login Button

```vue
<FeedbackModal
    :visible="authError"
    title="Authentication Required"
    message="You must be logged in to perform this action."
    :show-action-button="true"
    action-button-text="Login"
    action-icon="fas fa-sign-in-alt"
    @close="authError = false"
    @action="redirectToLogin">
</FeedbackModal>
```

### Custom Action Button

```vue
<FeedbackModal
    :visible="confirmDelete"
    title="Confirm Delete"
    message="Are you sure you want to delete this item?"
    :show-action-button="true"
    action-button-text="Delete"
    action-icon="fas fa-trash"
    @close="confirmDelete = false"
    @action="deleteItem">
</FeedbackModal>
```

### With Custom Content (using slot)

```vue
<FeedbackModal
    :visible="showDetails"
    title="Details"
    message="Multiple items require attention:"
    @close="showDetails = false">
    <ul class="mt-2">
        <li v-for="item in items" :key="item">{{ item }}</li>
    </ul>
</FeedbackModal>
```

## Styling

The modal uses Bootstrap classes and custom styles. All styles are scoped to prevent conflicts.

### Colors

- Header: Red gradient (`#dc3545` to `#c82333`)
- Background overlay: Black with 60% opacity
- Body: White background
- Footer: Light gray background (`#f8f9fa`)

### Animations

- **Slide down** on open
- **Slide up** on close
- **Fade** overlay transition

## Accessibility

- Modal is teleported to `<body>` for proper z-index stacking
- Prevents background scrolling when open
- Close button with proper ARIA label
- Keyboard-accessible (ESC key to close - can be added if needed)

## Browser Support

Works on all modern browsers:
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers

## Notes

- The modal automatically prevents body scrolling when open
- Body scrolling is restored when the modal closes or when the component is unmounted
- The modal is styled to work with Font Awesome icons
- Bootstrap CSS should be loaded in your project for proper styling

## Use Cases

- Error messages
- Success notifications
- Warning messages
- Confirmation dialogs
- Authentication prompts
- General user feedback

