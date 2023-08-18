<template>
    <div class="col-12">
        <div class="row">
            <h5 class="text-success font-italic mx-auto">Policy Discounts – Save More!</h5>
        </div>
        <div class="verify-box p-3 my-2">
            <div class="col-12">
                <div class="col-sm-6 mx-auto mt-2">
                    <span class="sale" @click="verifications.address = true">-5%</span>
                    <label>Address Verification Discount</label>
                    <select class="custom-select" v-model="verifications.address">
                        <option disabled selected></option>
                        <option :value="true">Yes</option>
                        <option :value="false">No</option>
                    </select>
                </div>

                <div class="col-sm-8 offset-sm-2 mx-auto mt-2" v-if="verifications.address">
                    <label>Street Address</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" :class="{'is-invalid': address_invalid, 'is-valid': address_validated}" v-model="address" placeholder="Street Name" @blur="validateAddress">
                        <div class="input-group-append" style="width: 10px">
                            <span class="input-group-text border-0 bg-transparent">
                                <img src="/images/input-loader.svg" draggable="false" height="20px" v-if="address_validating">
                                <i class="fa fa-check text-success" v-else-if="address_validated"></i>
                                <i class="fa fa-asterisk" v-else></i>
                            </span>
                        </div>
                        <div class="invalid-feedback" v-if="address_invalid">
                            {{ address_error }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12" v-if="address !== null">
                <hr/>

                <div class="col-sm-6 mx-auto mt-2">
                    <span class="sale" @click="verifications.email = true">-10%</span>
                    <label>Paperless Discount</label>
                    <select class="custom-select" v-model="verifications.email">
                        <option disabled selected></option>
                        <option :value="true">Yes</option>
                        <option :value="false">No</option>
                    </select>
                </div>

                <div class="col-sm-6 mx-auto mt-2" v-if="verifications.email">
                    <label>Email Address</label>
                    <input type="text" class="form-control" v-model="email">
                    <small id="emailHelp" class="form-text text-muted">We respect customer privacy and do not sell email addresses. Your quote progress will be saved for the next 90 days. This email address will only be used to send you information about your quote.</small>
                </div>
            </div>

            <div class="col-12" v-if="email !== null">
                <hr/>

                <div class="col-sm-6 mx-auto mt-2">
                    <span class="sale" @click="verifications.phone = true">-5%</span>
                    <label>Payment Text Alert Discount</label>
                    <select class="custom-select" v-model="verifications.phone">
                        <option disabled selected></option>
                        <option :value="true">Yes</option>
                        <option :value="false">No</option>
                    </select>
                </div>

                <div class="col-sm-6 mx-auto mt-2" v-if="verifications.phone">
                    <label>Contact Number</label>
                    <div class="input-group mb-2">
                        <masked-input type="tel"
                               class="form-control"
                               :class="{'is-invalid': phone_invalid, 'is-valid': phone_validated}"
                               v-model="phone"
                               mask="\+\1 (111) 111-1111"
                               @blur.native="validatePhone"
                        />
                        <div class="input-group-append" style="width: 10px">
                            <span class="input-group-text border-0 bg-transparent">
                                <img src="/images/input-loader.svg" draggable="false" height="20px" v-if="phone_validating">
                                <i class="fa fa-check text-success" v-else-if="phone_validated"></i>
                                <i class="fa fa-asterisk" v-else></i>
                            </span>
                        </div>
                        <div class="invalid-feedback" v-if="phone_invalid">
                            {{ phone_error }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row py-4 justify-content-center">
            <div class="col-5">
                <button class="btn btn-success btn-block btn-lg" @click="continueClicked()">Get Quote Now</button>
            </div>
        </div>
    </div>
</template>

<script>
    import MaskedInput from 'vue-masked-input'
    import EventBus from './event_bus'

    export default {
        props: ['driversDetails', 'vehiclesDetails', 'selectedCoverage', 'zip'],

        data() {
            return {
                verifications: {
                    address: true,
                    email: false,
                    phone: false,
                },

                address_invalid: false,
                address_validating: false,
                address_validated: false,
                address_error: "",

                phone_invalid: false,
                phone_validating: false,
                phone_validated: false,
                phone_error: "",

                address: null,
                email: null,
                phone: null
            }
        },

        components: {
            MaskedInput
        },

        computed: {
            // age21() {
            //     let lowEnd = 18,
            //         highEnd = 27,
            //         arr = [];
            //     while(lowEnd <= highEnd){
            //         arr.push(lowEnd++);
            //     }
            //
            //     return arr;
            // }
        },

        methods: {
            // getVehicleMake(vehicle) {
            //     let makeName = vehicle.makeOptions.filter(obj => {
            //         return obj.id === vehicle.make
            //     });
            //
            //     return makeName[0].name
            // },
            //
            // getVehicleCoverage(vehicle) {
            //     switch (vehicle.coverage) {
            //         case 'none':
            //             return "Full Liability";
            //         case '250':
            //         case '500':
            //         case '1000':
            //             return "$" + vehicle.coverage;
            //     }
            // },

            validateAddress(e) {
                const vm = this;
                let addressInput = e.target.value;

                let params = {
                    AddressLine1: addressInput,
                    AddressLine2: this.zip
                };

                this.address_validated = false;
                this.address_invalid = false;
                this.address_validating = true;

                axios.get('/api/validate-address', {
                    params: params
                }).then(response => {
                    if (!response.data.is_validated) {
                        vm.address_invalid = true;
                        vm.address_validated = false;
                        vm.address_error = response.data.response;
                        return;
                    }

                    vm.address_validating = false;
                    vm.address_invalid = false;
                    vm.address_validated = true;
                    vm.address_error = "";
                }).catch(error => {
                    vm.address_validating = false;
                    vm.address_invalid = true;
                    vm.address_validated = false;
                    vm.address_error = error.response.data.message;
                });
            },

            validatePhone(e) {
                const vm = this;
                let phoneInput = e.target.value;

                let params = {
                    phone_no: phoneInput,
                };

                this.phone_validated = false;
                this.phone_invalid = false;
                this.phone_validating = true;

                axios.get('/api/validate-phone', {
                    params: params
                }).then(response => {
                    vm.phone_validating = false;
                    vm.phone_invalid = false;
                    vm.phone_validated = true;
                    vm.phone_error = "";
                }).catch(error => {
                    vm.phone_validating = false;
                    vm.phone_invalid = true;
                    vm.phone_validated = false;
                    vm.phone_error = "Number should be a validated";
                });
            },

            continueClicked() {
                if (this.phone_validating) {
                    return;
                }

                if (!this.email && !this.phone) {
                    swal('Contact Information Required', 'Either email or phone needs to be provided', 'info');
                    return;
                }

                if (!this.address_validated) {
                    swal('Invalid Address', this.address_error || "Given address information is invalid", 'error');
                    return;
                }

                if (!this.email.includes('@') || !this.email.includes('.')) {
                    swal('Wrong Email Format', 'Email address you have provided is not in valid format', 'info');
                    return;
                }

                if (this.verifications.phone && !this.phone_validated) {
                    swal('Invalid Phone Number', "Contact number should be validated", 'error');
                    return;
                }

                if (!this.address) {
                    swal('Address Required', 'Without accurate address the rate may not be correct!', 'info');
                    return;
                }

                // let addressNumbers = this.address.match(/[0-9]+/g);
                // let addressLetters = this.address.match(/[a-zA-Z]+/g);
                //
                // if (!addressNumbers || !addressLetters) {
                //     swal('Wrong Address Format', 'Address you have provided is not in valid format', 'info');
                //     return;
                // } else if (addressLetters[0].length < 3) {
                //     swal('Wrong Address Format', 'Address you have provided is not in valid format', 'info');
                //     return;
                // }

                let data = {
                    address: this.address,
                    email: this.email,
                    phone: this.phone
                };
                EventBus.$emit('verify-page-data-submitted', data)
            }
        }

    }
</script>

<style>
    .verify-box {
        border: 1px solid green;
        border-radius: 2px;
    }

</style>