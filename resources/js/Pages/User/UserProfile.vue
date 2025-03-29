<template>
    <div class="mx-auto my-3 text-center display-5">
        {{ fullName }}
    </div>
    
    <div class="d-flex align-items-start justify-content-center">
        <div class="nav flex-column nav-pills me-3 col-md-2 col-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active text-start" id="businessDetails-tab" data-bs-toggle="pill" data-bs-target="#businessDetails" type="button" role="tab" aria-controls="businessDetails" aria-selected="true">Business Details</button>
            <button v-if="businessAccount" class="nav-link text-start" id="photoGallery-tab" data-bs-toggle="pill" data-bs-target="#photoGallery" type="button" role="tab" aria-controls="photoGallery" aria-selected="false">Photo Gallery</button>
            <button v-if="businessAccount" class="nav-link text-start" id="customersComments-tab" data-bs-toggle="pill" data-bs-target="#customersComments" type="button" role="tab" aria-controls="customersComments" aria-selected="false">Customers Comments</button>
            <button class="nav-link text-start" id="yourComments-tab" data-bs-toggle="pill" data-bs-target="#yourComments" type="button" role="tab" aria-controls="yourComments" aria-selected="false">My Comments</button>
            <button v-if="businessAccount" class="nav-link text-start" id="availability-tab" data-bs-toggle="pill" data-bs-target="#availability" type="button" role="tab" aria-controls="availability" aria-selected="false">Set Availability</button>
            <button v-if="businessAccount" class="nav-link text-start" id="customersAppointments-tab" data-bs-toggle="pill" data-bs-target="#customersAppointments" type="button" role="tab" aria-controls="customersAppointments" aria-selected="false">Customers Appointments</button>
            <button class="nav-link text-start" id="myAppointments-tab" data-bs-toggle="pill" data-bs-target="#myAppointments" type="button" role="tab" aria-controls="myAppointments" aria-selected="false">My Appointments</button>
        </div>
        <div class="tab-content col-9 col-md-8 mb-3" id="v-pills-tabContent">
            <!-- Business details pane -->
            <div class="tab-pane fade show active" id="businessDetails" role="tabpanel" aria-labelledby="businessDetails-tab" tabindex="0">
                <div class="accordion" id="accordionExample">
                    <!-- User Details section -->
                    <!-- Profile Photo Section -->
                    <ProfilePhotoSection2></ProfilePhotoSection2>
                    
                    <!-- {{-- First name accordion --}} -->
                    <FirstNameSection :firstName="user.first_name" :userId="user.id"></FirstNameSection>
                    <!-- <FieldAccordion :fieldName="'first_name'" :fieldVal="user.first_name" :userId="user.id"></FieldAccordion> -->
                    
                    <!-- {{-- Last name accordion --}} -->
                    <LastNameSection :lastName="user.last_name" :userId="user.id"></LastNameSection>
                    <!-- <FieldAccordion :fieldName="'last_name'" :fieldVal="user.last_name" :userId="user.id"></FieldAccordion> -->
                    
                    <!-- {{-- Gender accordion --}} -->
                    <GenderSection :gender="user.gender" :userId="user.id"></GenderSection>
                    
                    <!-- {{-- Username accordion --}} -->
                    <UsernameSection :username="user.username" :userId="user.id"></UsernameSection>                    
                    
                    <!-- {{-- Email accordion --}} -->
                    <EmailSection :email="user.email" :userId="user.id"></EmailSection>
                    
                    <!-- {{-- Phone number accordion --}} -->
                    <PhoneNumberSection :phoneNumber="user.phone_number" :userId="user.id"></PhoneNumberSection>
                    
                    <!-- {{-- Password accordion --}} -->
                    <PasswordSection :userId="user.id"></PasswordSection>

                    <!-- {{-- Business name accordion --}} -->
                    <BusinessNameSection v-if="businessAccount" :businessName="user.business_category.business_name" :userId="user.id"></BusinessNameSection>

                    <!-- {{-- Business description accordion --}} -->
                    <BusinessDescriptionSection v-if="businessAccount" :businessDescription="user.business_category.business_description" :userId="user.id"></BusinessDescriptionSection>

                    <!-- {{-- Business page accordion --}} -->
                    <BusinessPageSection v-if="businessAccount" :businessPage="user.business_category.business_page" :userId="user.id"></BusinessPageSection>
                    
                    <!-- {{-- Address Line 1 accordion --}} -->
                    <AddressLine1Section v-if="businessAccount" :addressLine1="user.address.address_line_1" :userId="user.id"></AddressLine1Section>

                    <!-- {{-- Address Line 2 accordion --}} -->
                    <AddressLine2Section v-if="businessAccount" :addressLine2="user.address.address_line_2" :userId="user.id"></AddressLine2Section>
                    
                    <!-- {{-- Address Line 3 accordion --}} -->
                    <AddressLine3Section v-if="businessAccount" :addressLine3="user.address.address_line_3" :userId="user.id"></AddressLine3Section>
                    
                    <!-- {{-- State accordion --}} -->
                    <StateSection v-if="businessAccount" :state="user.address.state" :userId="user.id"></StateSection>
                    
                    <!-- {{-- Town accordion --}} -->
                    <TownSection v-if="businessAccount" :town="user.address.town" :userId="user.id"></TownSection>
                    
                    <!-- {{-- Business category accordion --}} -->
                    <BusinessCategorySection v-if="businessAccount" :businessCategory="user.business_category" :userId="user.id"></BusinessCategorySection>
                    
                    <!-- {{-- Artisans accordion --}} -->
                    <ArtisansSection v-if="isArtisan" :allArtisans="allArtisans" :selectedArtisans="isArtisan" :userId="user.id"></ArtisansSection>
                    
                    <!-- {{-- Mobile market accordion --}} -->
                    <MobileMarketSection v-if="businessAccount" :allProducts="allProducts" :selectedProducts="isMobileMarketer" :userId="user.id"></MobileMarketSection>
                    
                    <!-- {{-- Technicians accordion --}} -->
                    <TechnicalServiceSection v-if="businessAccount" :allTechnicalServices="allTechnicalServices" :selectedTechnicians="isMechanic" :userId="user.id"></TechnicalServiceSection>
                    
                    <!-- Vehicle Category accordion -->
                    <VehicleCategorySection v-if="businessAccount" :allVehicleCategories="allVehicleCategories" :selectedVehCategories="hasTechVehCategory" :userId="user.id" :techOrSpare="'tech'"></VehicleCategorySection>
                    
                    <!-- {{-- Car brands accordion --}} -->
                    <CarBrandsSection v-if="businessAccount" :allCarBrands="allCarBrands" :selectedCarBrands="vehicleBrands.tech_car" :userId="user.id"></CarBrandsSection>
                    
                    <!-- {{-- Bus brands accordion --}} -->
                    <BusBrandsSection v-if="businessAccount" :allBusBrands="allBusBrands" :selectedBusBrands="vehicleBrands.tech_bus" :userId="user.id"></BusBrandsSection>
                    
                    <!-- {{-- Truck brnads accordion --}} -->
                    <TruckBrandsSection v-if="businessAccount" :allTruckBrands="allTruckBrands" :selectedTruckBrands="vehicleBrands.tech_truck" :userId="user.id"></TruckBrandsSection>
                    
                    <!-- {{-- Spare part sellers accordion --}} -->
                    <SparePartSection v-if="businessAccount" :allSpareParts="allSpareParts" :selectedSpareParts="isSparePartSeller" :userId="user.id"></SparePartSection>
                    
                    <!-- Vehicle Category accordion -->
                    <VehicleCategorySection v-if="businessAccount" :allVehicleCategories="allVehicleCategories" :selectedVehCategories="hasSpartVehCategory" :userId="user.id" :techOrSpare="'spare'"></VehicleCategorySection>
                    
                    <!-- {{-- Car brands accordion --}} -->
                    <CarBrandsSection v-if="businessAccount" :allCarBrands="allCarBrands" :selectedCarBrands="vehicleBrands.sPart_car" :userId="user.id"></CarBrandsSection>
                    
                    <!-- {{-- Bus brands accordion --}} -->
                    <BusBrandsSection v-if="businessAccount" :allBusBrands="allBusBrands" :selectedBusBrands="vehicleBrands.sPart_bus" :userId="user.id"></BusBrandsSection>
                    
                    <!-- {{-- Truck brnads accordion --}} -->
                    <TruckBrandsSection v-if="businessAccount" :allTruckBrands="allTruckBrands" :selectedTruckBrands="vehicleBrands.sPart_truck" :userId="user.id"></TruckBrandsSection>
                </div>
            </div>
            <!-- End of Business details pane -->
    
            <!-- Photo gallery -->
            <div v-if="businessAccount" class="tab-pane fade" id="photoGallery" role="tabpanel" aria-labelledby="photoGallery-tab" tabindex="0">
                <div class="gallery">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md 10">
                                <div class="row justify-content-evenly">
                                    
                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(1) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <p class="card-text mb-1 px-2">Image caption</p>
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(1) class="img-fluid" alt="">
                                                        <p class="card-text my-2 px-2 bg-dark text-light rounded">A beautiful cruise boat parked in the harbor waiting a beautiful environment.</p>
                                                        <a href="#" class="text-decoration-none me-3 text-body"><i class="fa-solid fa-thumbs-up pe-2"></i>20</a>
                                                        <div class="d-flex my-3">
                                                            <button class="btn btm-sm col-auto bg-danger text-light me-3">Edit Caption</button>
                                                            <button class="btn btm-sm col-auto bg-danger text-light me-3">Set Image as Cover Photo</button>
                                                            <button class="btn btm-sm col-auto bg-danger text-light">Delete Photo</button>
                                                        </div>

                                                        <div class="col-12 rounded bg-dark text-light p-2 mb-3">
                                                            <form action="">
                                                                <!-- <textarea class="rounded col-12 mt-3" name="" id="" placeholder="Enter your comment" rows="3"></textarea> -->
                                                                <textarea class="rounded form-control my-3" name="" id="" placeholder="Edit Caption" rows="3"></textarea>
                                                                <div class="d-flex justify-content-between">
                                                                    <button class="btn btn-sm btn-danger">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <div class="col-12 rounded bg-dark text-light p-2">
                                                            <p class="pt-2">5 comment(s)</p>
                                                            <div class="col-12 border-bottom pt-2">
                                                                <div class="row justify-content-between align-content-middle">
                                                                    <div class="col-auto">
                                                                        <div class="d-flex mt-2 mb-1 align-middle">
                                                                            <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                                                            <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                                                        </div>
                                                                    </div>
                                                                    <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                                                                </div>
                                                                <p class="col-12 bg-white text-body rounded">
                                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?
                                                                </p>
                                                            </div>
                                                            <div class="col-12 border-bottom pt-2">
                                                                <div class="row justify-content-between align-content-middle">
                                                                    <div class="col-auto">
                                                                        <div class="d-flex mt-2 mb-1 align-middle">
                                                                            <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                                                            <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                                                        </div>
                                                                    </div>
                                                                    <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                                                                </div>
                                                                <p class="col-12 bg-white text-body rounded">
                                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(2) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                        <p class="card-text mb-1 px-2">Image caption</p>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(2) class="img-fluid" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(3) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal2">
                                        <p class="card-text mb-1 px-2">Image caption</p>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(3) class="img-fluid" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(4) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal3">
                                        <p class="card-text mb-1 px-2">Image caption</p>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(4) class="img-fluid" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(5) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal4">
                                        <p class="card-text mb-1 px-2">Image caption</p>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(5) class="img-fluid" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card m-3 shadow" style="width: 200px;">
                                        <img :src=imagePath(6) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal5">
                                        <p class="card-text mb-1 px-2">Image caption</p>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img :src=imagePath(6) class="img-fluid" alt="">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Photo gallery -->
    
            <!-- Contents for Customers Comments -->
            <div v-if="businessAccount" class="tab-pane fade" id="customersComments" role="tabpanel" aria-labelledby="customersComments-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-xl-8 rounded text-light pb-3" style="background-color: #040D14">
                        <p class="pt-2">5 comment(s)</p>
                        <div class="col-12 border-bottom py-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?
                            </p>
                            <div class="d-flex justify-content-end">
                                <button class="btn btm-sm border border-light text-light" style="background-color: #040D14">Reply</button>
                            </div>
                        </div>

                        <div class="col-12 border-bottom py-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ad cumque adipisci possimus voluptatum? Asperiores labore, adipisci id nostrum ab ut voluptatum accusantium? Nulla similique ut aspernatur laudantium. Debitis, vitae nostrum.
                            </p>
                            <div class="d-flex justify-content-end">
                                <button class="btn btm-sm border border-light text-light" style="background-color: #040D14">Reply</button>
                            </div>

                            <!-- reply -->
                            <div class="row justify-content-end">
                                <div class="col-10">
                                    <div class="row justify-content-between align-content-middle">
                                        <div class="col-auto">
                                            <div class="d-flex mt-2 mb-1 align-middle">
                                                <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                                <p class="col-auto mb-1">You replied</p>
                                            </div>
                                        </div>
                                        <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                                    </div>
                                    <p class="col-12 bg-white text-body rounded">
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ea quia magni illo ipsam est ducimus accusantium quisquam libero ut soluta similique, natus molestiae voluptas architecto? Inventore excepturi aliquid soluta placeat!
                                    </p>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-10">
                                    <div class="row justify-content-between align-content-middle">
                                        <div class="col-auto">
                                            <div class="d-flex mt-2 mb-1 align-middle">
                                                <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                                <p class="col-auto mb-1">You replied</p>
                                            </div>
                                        </div>
                                        <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                                    </div>
                                    <p class="col-12 bg-white text-body rounded">
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ea quia magni illo ipsam est ducimus accusantium quisquam libero ut soluta similique, natus molestiae voluptas architecto? Inventore excepturi aliquid soluta placeat!
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 border-bottom py-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus iusto ut asperiores, aliquid repellat fugit nam omnis perspiciatis deserunt est, ea ex enim quae cum! Tempora veritatis similique error quos!
                            </p>
                            <textarea class="rounded form-control my-3" name="" id="" placeholder="Reply" rows="1"></textarea>
                            <div class="d-flex justify-content-end">
                                <button class="btn btm-sm border border-light text-light" style="background-color: #040D14">Reply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Contents for Customers Comments -->
            
            <!-- Contents for My Comments -->
            <div class="tab-pane fade" id="yourComments" role="tabpanel" aria-labelledby="yourComments-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-xl-8 rounded text-light pb-3" >  <!-- style="background-color: #040D14" -->
                        <p class="pt-2 text-body">5 comment(s)</p>
                        <div class="col-12 border-bottom pt-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1 text-body">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2 text-body">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?
                            </p>
                        </div>

                        <div class="col-12 border-bottom pt-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1 text-body">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2 text-body">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ad cumque adipisci possimus voluptatum? Asperiores labore, adipisci id nostrum ab ut voluptatum accusantium? Nulla similique ut aspernatur laudantium. Debitis, vitae nostrum.
                            </p>
                        </div>

                        <div class="col-12 border-bottom pt-2">
                            <div class="row justify-content-between align-content-middle">
                                <div class="col-auto">
                                    <div class="d-flex mt-2 mb-1 align-middle">
                                        <img class="col-auto rounded-circle me-2" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                        <p class="col-auto mb-1 text-body">Nna-ayua Okafor</p>
                                    </div>
                                </div>
                                <p class="col-auto pt-2 text-body">December 18, 2024 at 5:18 PM</p>
                            </div>
                            <p class="col-12 bg-white text-body rounded">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus iusto ut asperiores, aliquid repellat fugit nam omnis perspiciatis deserunt est, ea ex enim quae cum! Tempora veritatis similique error quos!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Contents for My Comments -->
    
            <!-- Contents for Set Availability -->
            <div v-if="businessAccount" class="tab-pane fade" id="availability" role="tabpanel" aria-labelledby="availability-tab" tabindex="0">
                Contents for Set Availability
            </div>
            <!-- End of Contents for Set Availability -->
    
            <!-- Contents for Customers Appointments -->
            <div v-if="businessAccount" class="tab-pane fade" id="customersAppointments" role="tabpanel" aria-labelledby="customersAppointments-tab" tabindex="0">                    
                <div class="row gap-3 gx-1">
                    <div class="col-md">
                        <p class="bg-danger text-light text-center">Appointments requested with technicians</p>
                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h5 class="card-title text-center">Appointment Details</h5>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Technician Name:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Nna-ayua Okafor</p>
                                </div>                                    
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Phone Number:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">09093336969</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Day:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Friday, December 20, 2024</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Time:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">8:00 AM - 9:00 AM, 9:00 AM - 10:00 AM, 10:00 AM - 11:00 AM</p>
                                    <!-- <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">
                                        <ul class="list-inline">
                                            <li class="list-inline-item">8:00 AM - 9:00 AM,</li>
                                            <li class="list-inline-item">9:00 AM - 10:00 AM,</li>
                                            <li class="list-inline-item">10:00 AM - 11:00 AM</li>
                                        </ul>
                                    </p> -->
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Reason:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorum at sit numquam! Ut, ipsa! Ducimus animi sapiente iusto minima, repudiandae natus fugit eligendi sequi, beatae placeat nam modi optio?</p>
                                </div>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h5 class="card-title text-center">Appointment Details</h5>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Technician Name:</span>
                                    Nna-ayua Okafor</p>
                                </div>                                    
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Phone Number:</span>
                                    09093336969</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Day:</span>
                                    Friday, December 20, 2024</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Time:</span>
                                    8:00 AM - 9:00 AM, 9:00 AM - 10:00 AM</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Reason:</span>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorum at sit numquam! Ut, ipsa! Ducimus animi sapiente iusto minima, repudiandae natus fugit eligendi sequi, beatae placeat nam modi optio?</p>
                                </div>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have 1 appointment waiting confirmation</h6>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment request declined.</h6>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment request canceled.</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md">
                        <p class="bg-danger text-light text-center">Appointments confirmed with technicians</p>
                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment confirmed yet.</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Contents for Customers Appointments -->
    
            <!-- Contents for My Appointments -->
            <div class="tab-pane fade" id="myAppointments" role="tabpanel" aria-labelledby="myAppointments-tab" tabindex="0">                    
                <div class="row gap-3 gx-1">
                    <div class="col-md">
                        <p class="bg-danger text-light text-center">Appointments requested with technicians</p>
                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h5 class="card-title text-center">Appointment Details</h5>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Technician Name:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Nna-ayua Okafor</p>
                                </div>                                    
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Phone Number:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">09093336969</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Day:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Friday, December 20, 2024</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Time:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">8:00 AM - 9:00 AM, 9:00 AM - 10:00 AM, 10:00 AM - 11:00 AM</p>
                                    <!-- <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">
                                        <ul class="list-inline">
                                            <li class="list-inline-item">8:00 AM - 9:00 AM,</li>
                                            <li class="list-inline-item">9:00 AM - 10:00 AM,</li>
                                            <li class="list-inline-item">10:00 AM - 11:00 AM</li>
                                        </ul>
                                    </p> -->
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Reason:</p>
                                    <p class="card-text col-xs-8 ps-0 ps-sm-2 mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorum at sit numquam! Ut, ipsa! Ducimus animi sapiente iusto minima, repudiandae natus fugit eligendi sequi, beatae placeat nam modi optio?</p>
                                </div>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h5 class="card-title text-center">Appointment Details</h5>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Technician Name:</span>
                                    Nna-ayua Okafor</p>
                                </div>                                    
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Phone Number:</span>
                                    09093336969</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Day:</span>
                                    Friday, December 20, 2024</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Time:</span>
                                    8:00 AM - 9:00 AM, 9:00 AM - 10:00 AM</p>
                                </div>
                                <div class="d-block d-sm-flex">
                                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-3"><span class="card-text col-xs-4 fw-semibold mb-0 mb-sm-3">Appointment Reason:</span>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam dolorum at sit numquam! Ut, ipsa! Ducimus animi sapiente iusto minima, repudiandae natus fugit eligendi sequi, beatae placeat nam modi optio?</p>
                                </div>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have 1 appointment waiting confirmation</h6>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment request declined.</h6>
                            </div>
                        </div>

                        <div class="card col-12 mb-3">
                            <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment request canceled.</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md">
                        <p class="bg-danger text-light text-center">Appointments confirmed with technicians</p>
                        <div class="card col-12 mb-3">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-body-secondary">You have no appointment confirmed yet.</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Contents for My Appointments -->
        </div>
    </div>
</template>


<script>
    import FieldAccordion from './Components/FieldAccordion.vue';
    import ProfilePhotoSection from './Components/ProfilePhotoSection.vue';
    import ProfilePhotoSection2 from './Components/ProfilePhotoSection2.vue';
    import FirstNameSection from './Components/FirstNameSection.vue';
    import LastNameSection from './Components/LastNameSection.vue';
    import GenderSection from './Components/GenderSection.vue';
    import UsernameSection from './Components/UsernameSection.vue';
    import EmailSection from './Components/EmailSection.vue';
    import PhoneNumberSection from './Components/PhoneNumberSection.vue';
    import PasswordSection from './Components/PasswordSection.vue';
    import CommentCard from './Components/CommentCard.vue';
    import AppointmentDetails from './Components/AppointmentDetails.vue';
    import AppointmentDetails1 from './Components/AppointmentDetails1.vue';
    import BusinessNameSection from './Components/BusinessNameSection.vue';
    import AddressLine1Section from './Components/AddressLine1Section.vue';
    import AddressLine2Section from './Components/AddressLine2Section.vue';
    import AddressLine3Section from './Components/AddressLine3Section.vue';
    import StateSection from './Components/StateSection.vue';
    import TownSection from './Components/TownSection.vue';
    import BusinessCategorySection from './Components/BusinessCategorySection.vue';
    import BusinessDescriptionSection from './Components/BusinessDescriptionSection.vue';
    import ArtisansSection from './Components/ArtisansSection.vue';
    import MobileMarketSection from './Components/MobileMarketSection.vue';
    import TechnicalServiceSection from './Components/TechnicalServiceSection.vue';
    import SparePartSection from './Components/SparePartSection.vue';
    import CarBrandsSection from './Components/CarBrandsSection.vue';
    import BusBrandsSection from './Components/BusBrandsSection.vue';
    import TruckBrandsSection from './Components/TruckBrandsSection.vue';
    import VehicleCategorySection from './Components/VehicleCategorySection.vue';
    import BusinessPageSection from './Components/BusinessPageSection.vue';

    // import Mixins
    import MethodsMixin from './Mixins/MethodsMixin.js';

    export default {
        components: {
            FieldAccordion, ProfilePhotoSection, ProfilePhotoSection2, FirstNameSection, 
            LastNameSection, GenderSection, UsernameSection, EmailSection, PhoneNumberSection, 
            PasswordSection, CommentCard, AppointmentDetails, AppointmentDetails1, 
            BusinessNameSection, AddressLine1Section, AddressLine2Section, AddressLine3Section, 
            StateSection, TownSection, BusinessCategorySection, BusinessDescriptionSection, 
            ArtisansSection, MobileMarketSection, TechnicalServiceSection, SparePartSection,
            CarBrandsSection, BusBrandsSection, TruckBrandsSection, VehicleCategorySection,
            BusinessPageSection
        },
        mixins: [MethodsMixin],
        props: ['user', 'userCategories', 'techVehCategories', 'sPartVehCategories', 'vehicleBrands', 'allArtisans', 'allProducts',
            'allTechnicalServices', 'allSpareParts', 'allVehicleCategories', 'allCarBrands',
            'allBusBrands', 'allTruckBrands'
        ],
        emits: [],
        data() {
            return {
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            }
        },
        computed: {
            fullName() {
                return this.capitalizeFirstLetter(this.user.first_name) + " " + this.capitalizeFirstLetter(this.user.last_name);
            },
            businessAccount() {
                return (this.user.account_type === 'business') ? true : false;
            },
            isArtisan() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.userCategories.artisan === "object" && (this.userCategories.artisan !== "undefined" || this.userCategories.artisan !== null)) {
                    return (Object.entries(this.userCategories.artisan).length > 0) ? this.userCategories.artisan : false;
                } else {
                    return false;
                }
            },
            isMobileMarketer() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.userCategories.mobile_marketer === "object" && (this.userCategories.mobile_marketer !== "undefined" || this.userCategories.mobile_marketer !== null)) {
                    return (Object.entries(this.userCategories.mobile_marketer).length > 0) ? this.userCategories.mobile_marketer : false;
                } else {
                    return false;
                }
            },
            isMechanic() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.userCategories.mechanic === "object" && (this.userCategories.mechanic !== "undefined" || this.userCategories.mechanic !== null)) {
                    return (Object.entries(this.userCategories.mechanic).length > 0) ? this.userCategories.mechanic : false;
                } else {
                    return false;
                }
            },
            isSparePartSeller() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.userCategories.spare_part_seller === "object" && (this.userCategories.spare_part_seller !== "undefined" || this.userCategories.spare_part_seller !== null)) {
                    return (Object.entries(this.userCategories.spare_part_seller).length > 0) ? this.userCategories.spare_part_seller : false;
                } else {
                    return false;
                }
            },
            hasTechVehCategory() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                // check if vehicle brands exists
                if (typeof this.techVehCategories === "object" && (this.techVehCategories !== "undefined" || this.techVehCategories !== null)) {
                    return (Object.entries(this.techVehCategories).length > 0) ? this.techVehCategories : false;
                } else {
                    return false;
                }
            },
            hasSpartVehCategory() {
                if (this.user.account_type !== 'business') {
                    return false
                }
                if (typeof this.sPartVehCategories === "object" && (this.sPartVehCategories !== "undefined" || this.sPartVehCategories !== null)) {
                    return (Object.entries(this.sPartVehCategories).length > 0) ? this.sPartVehCategories : false;
                } else {
                    return false;
                }
            }
        }
    }
</script>