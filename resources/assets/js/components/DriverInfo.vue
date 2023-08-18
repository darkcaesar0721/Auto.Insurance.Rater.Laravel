<template>
    <div class="col-12">
        <form class="form-inline justify-content-center">
            <label class="my-1 mr-2" for="no-of-drivers">Number of Drivers</label>
            <select class="custom-select my-1 mr-sm-2" id="no-of-drivers" v-model.number="no_of_drivers">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <img class="number-arrow" src="/images/arrow.svg" v-if="no_of_drivers === 0"/>
        </form>

        <div v-for="(driver, index) in drivers_details" class="driver-box p-3 my-3" :key="index">
            <i class="fa fa-user mr-1"></i> <strong>Driver {{ index+1 }}</strong>
            <i class="fa fa-remove pull-right remove-icon pointer" @click="removeDriver(index)"></i>

            <hr/>

            <div>
                <form class="mt-1 align-content-center">
                    <div class="row align-items-end">
                        <div class="col-12 col-sm-4 py-1">
                            <label>First Name:</label>
                            <input type="text" class="form-control text-uppercase" v-model="driver.first_name" data-title="First Name" :data-index="index" @change="forceUpdate">
                        </div>
                        <div class="col-12 col-sm-2 py-1">
                            <label>Gender <br/></label>
                            <select class="custom-select" v-model="driver.gender" data-title="Gender" :data-index="index">
                                <option selected disabled></option>
                                <option value="male">M</option>
                                <option value="female">F</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-4 py-1">
                            <label>Birth Date:</label>
                            <masked-input class="form-control" placeholder="mm/dd/yyyy" mask="11/11/1111" v-model="driver.dob" data-title="Birth Date" :data-index="index" @blur.native="checkDate(index)" />
                        </div>

                        <div class="col-12 col-sm-2 py-1" v-if="getAge(driver.dob) < 27 && getAge(driver.dob) > 17">
                            <!--<span class="sale">-5%</span>-->
                            <label>Good Student <br/>
                                <!--<span class="small">Up Age 27</span>-->
                            </label>
                            <select class="custom-select" v-model="driver.good_student" data-title="Good Student" :data-index="index">
                                <option selected disabled></option>
                                <option :value="true">Yes</option>
                                <option :value="false">No</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="ageIsValid(driver.dob) && driver.first_name">
                        <hr />

                        <div class="row align-content-center">
                            <div class="col-12 col-sm-5 py-1">
                                <label>Licensing:</label>
                                <select class="custom-select" v-model="driver.licensing_status" data-title="Licensing" :data-index="index" @change="licensingChanged(index)">
                                    <option disabled>Choose...</option>
                                    <option value="california">California Drivers License</option>
                                    <option value="other_state">Other State (USA Only)</option>
                                    <option value="foreign">Foreign License</option>
                                    <option value="marticula_consular">Marticula Consular</option>
                                    <option value="none">None</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-5 py-1" v-if="driver.licensing_status === 'california' || driver.licensing_status === 'other_state'">
                                <label>Status:</label>
                                <select class="custom-select" v-model="driver.license_status" data-title="Status" :data-index="index" @change="forceUpdate">
                                    <option disabled>Choose...</option>
                                    <option value="valid">Valid</option>
                                    <option value="expired">Expired</option>
                                    <option value="valid_need_sr22">Valid Need SR-22</option>
                                    <option value="suspended_sr22">Suspended Need SR-22</option>
                                    <option value="revoked">Revoked</option>
                                    <option value="none">None</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-2 m-auto py-1" v-if="(driver.license_status === 'valid_need_sr22' || driver.license_status === 'suspended_sr22') && driver.licensing_status !== 'other_state'">
                                <label>SR-22:</label>
                                <select class="custom-select" v-model="driver.sr_22" data-title="SR-22" :data-index="index" @change="forceUpdate">
                                    <option disabled>Choose...</option>
                                    <option :value="true">Yes</option>
                                    <option :value="false">No</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-2 m-auto py-1" v-if="driver.licensing_status === 'other_state'">
                                <label>State:</label>
                                <select class="custom-select" v-model="driver.state" data-title="State" :data-index="index" @change="forceUpdate">
                                    <option disabled>Choose...</option>
                                    <option v-for="state in states" :value="state">{{ state }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div v-if="showDriverLicenseSection(index)">
                        <hr />

                        <div class="row align-items-end">
                            <div class="col-sm-3 col-12 mt-2" v-if="driver.license_status !== 'valid' && (driver.licensing_status === 'other_state' || driver.licensing_status === 'california')">
                                <!--<span class="sale">-20%</span>-->
                                <label>Good Driver <br/> Discount</label>
                                <select class="custom-select" v-model="driver.good_driver" data-title="Good Driver" :data-index="index" @change="forceUpdate">
                                    <option selected disabled></option>
                                    <option :value="true">Yes</option>
                                    <option :value="false">No</option>
                                </select>
                            </div>

                            <div class="col-sm-4 col-12" v-if="driver.good_driver || driver.license_status === 'valid'">
                                <label :for="'license_no_' + index">Driver's License No</label>
                                <input type="text" class="form-control text-uppercase" :id="'license_no_' + index" placeholder="A00000000" data-title="License Number" :data-index="index" v-model="driver.license_no" @keydown="licenseNoCheck(index, false, $event)">
                            </div>

                            <div class="col-sm-4 col-12">
                                <label>Driver's Last Name</label>
                                <input type="text" class="form-control text-uppercase" v-model="driver.last_name" data-title="Last Name" :data-index="index" @change="forceUpdate" >
                            </div>
                        </div>
                    </div>

                    <div v-if="driver.last_name !== null">
                        <hr />

                        <div class="row align-content-center">
                            <div class="col-12 col-sm-6 py-1">
                                <label>Marital Status:</label>
                                <select class="custom-select" v-model="driver.marital_status" data-title="Marital Status" :data-index="index" @change="changedMaritalStatus(index, $event)">
                                    <option disabled>Choose...</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                </select>
                            </div>

                            <div class="col-12 col-sm-6 m-auto py-1" v-if="driver.marital_status === 'married'">
                                <label>Add Spouse As Driver:</label>
                                <select class="custom-select" v-model="driver.spouse_is_driver" data-title="Spouse Is Driver" :data-index="index" @change="changedSpouseDriver(index, $event)">
                                    <option disabled>Choose...</option>
                                    <option :value="true">Yes</option>
                                    <option :value="false">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-if="!driver.spouse_is_driver && driver.marital_status === 'married'">
                        <div class="col-12">
                            <hr/>
                        </div>
                        <div class="col-12 text-center py-1">
                            <label class="text-danger">EXCLUDE SPOUSE</label>
                        </div>
                    </div>


                    <div v-if="drivers_details[index].marital_status === 'married'">
                        <hr />
                        <div class="row align-content-center">
                            <div class="col-12 col-sm-4 py-1">
                                <label>Spouse - First Name:</label>
                                <input type="text" class="form-control text-uppercase" data-title="Spouse's First Name" :data-index="index" v-model="driver.wife_first_name">
                            </div>
                            <div class="col-12 col-sm-4 py-1">
                                <label>Spouse - Last Name:</label>
                                <input type="text" class="form-control text-uppercase" data-title="Spouse's Last Name" :data-index="index" v-model="driver.wife_last_name">
                            </div>
                            <div class="col-12 col-sm-4 py-1">
                                <label>Spouse - Birth Date:</label>
                                <masked-input class="form-control" placeholder="mm/dd/yyyy" mask="11/11/1111" data-title="Spouse's Birth Date" :data-index="index" v-model="driver.wife_dob" @blur.native="checkDate(index, true)" />
                            </div>
                        </div>

                        <div v-if="driver.spouse_is_driver">
                            <hr />

                            <div class="row align-content-center">
                                <div class="col-12 col-sm-5 py-1">
                                    <label>Spouse - Licensing:</label>
                                    <select class="custom-select" v-model="driver.wife_licensing_status" data-title="Spouse's Licensing" :data-index="index" @change="forceUpdate">
                                        <option disabled>Choose...</option>
                                        <option value="california">California Drivers License</option>
                                        <option value="other_state">Other State (USA Only)</option>
                                        <option value="foreign">Foreign License</option>
                                        <option value="marticula_consular">Marticula Consular</option>
                                        <option value="none">None</option>
                                    </select>
                                </div>

                                <div class="col-12 col-sm-5 py-1" v-if="driver.wife_licensing_status === 'california' || driver.wife_licensing_status === 'other_state'">
                                    <label>Spouse - License Status:</label>
                                    <select class="custom-select" v-model="driver.wife_license_status" data-title="Spouse's License Status" :data-index="index" @change="forceUpdate">
                                        <option disabled>Choose...</option>
                                        <option value="valid">Valid</option>
                                        <option value="expired">Expired</option>
                                        <option value="valid_need_sr22">Valid Need SR-22</option>
                                        <option value="suspended_sr22">Suspended Need SR-22</option>
                                        <option value="revoked">Revoked</option>
                                        <option value="none">None</option>
                                    </select>
                                </div>

                                <div class="col-12 col-sm-2 py-1" v-if="driver.wife_license_status === 'valid_need_sr22' || driver.wife_license_status === 'suspended_sr22'">
                                    <label>SR-22:</label>
                                    <select class="custom-select" data-title="Spouse's SR-22" :data-index="index" v-model="driver.wife_sr_22">
                                        <option disabled>Choose...</option>
                                        <option :value="true">Yes</option>
                                        <option :value="false">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-content-center py-2">
                                <div class="col-sm-4 col-12" v-if="driver.wife_licensing_status === 'california' || driver.wife_licensing_status === 'other_state'">
                                    <label :for="'wife_license_no_' + index">Spouse License No</label>
                                    <input type="text" class="form-control" :id="'wife_license_no_' + index" placeholder="A00000000" data-title="Spouse's License No" :data-index="index" v-model="driver.wife_license_no" @keydown="licenseNoCheck(index, true, $event)">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row py-4 justify-content-center" v-if="no_of_drivers">
            <div class="col-5">
                <button class="btn btn-primary btn-block" @click="continueClicked()">Continue</button>
            </div>
        </div>
    </div>
</template>

<script>
    import MaskedInput from 'vue-masked-input'
    import EventBus from './event_bus'
    import moment from 'moment'

    export default {
        props: ['rootDriversDetails'],

        data() {
            return {
                no_of_drivers: this.rootDriversDetails.length,
                drivers_details: this.rootDriversDetails
            }
        },

        components: {
            MaskedInput
        },

        mounted() {
            if (this.no_of_drivers == 0) {
                this.no_of_drivers = 1; 
            }
        },

        watch: {
            no_of_drivers(val) {
                for (let i = 0; i < val; i++) {
                    if (!this.drivers_details[i]) {
                        this.drivers_details[i] = {
                            first_name: null,
                            last_name: null,
                            gender: null,
                            dob: null,
                            good_driver: false,

                            marital_status: null,

                            licensing_status: null,
                            license_status: null,
                            sr_22: null,
                            spouse_is_driver: false,
                            wife_first_name: null,
                            wife_last_name: null,
                            wife_dob: null,

                            wife_licensing_status: null,
                            wife_license_status: null,
                            wife_license_no: null,
                            wife_sr_22: null,
                        }
                    }
                }

                let keys = Object.keys(this.drivers_details);

                for (let key of keys) {
                    if (key > this.no_of_drivers - 1) {
                        this.drivers_details.pop()
                    }
                }
            }
        },

        computed: {
            states() {
                return [ "AK",
                    "AL",
                    "AR",
                    "AS",
                    "AZ",
                    "CO",
                    "CT",
                    "DC",
                    "DE",
                    "FL",
                    "GA",
                    "GU",
                    "HI",
                    "IA",
                    "ID",
                    "IL",
                    "IN",
                    "KS",
                    "KY",
                    "LA",
                    "MA",
                    "MD",
                    "ME",
                    "MI",
                    "MN",
                    "MO",
                    "MS",
                    "MT",
                    "NC",
                    "ND",
                    "NE",
                    "NH",
                    "NJ",
                    "NM",
                    "NV",
                    "NY",
                    "OH",
                    "OK",
                    "OR",
                    "PA",
                    "PR",
                    "RI",
                    "SC",
                    "SD",
                    "TN",
                    "TX",
                    "UT",
                    "VA",
                    "VI",
                    "VT",
                    "WA",
                    "WI",
                    "WV",
                    "WY"];
            }
        },

        methods: {
            checkDate(index, isWife = false) {
                let key = isWife ? 'wife_dob' : 'dob';

                if (this.drivers_details[index][key].includes('_')) {
                    let driver = this.drivers_details[index];
                    driver[key] = '';
                    this.$set(this.drivers_details, index, driver);
                }

                this.forceUpdate()
            },

            forceUpdate() {
                this.$forceUpdate()
            },

            changedMaritalStatus(index, e) {
                if (this.drivers_details[index].marital_status !== 'married') {
                    this.drivers_details[index].spouse_is_driver = null;
                    this.drivers_details[index].wife_first_name = null;
                    this.drivers_details[index].wife_dob = null;
                    this.changedSpouseDriver(index);
                }
                this.forceUpdate();
            },

            changedSpouseDriver(index) {
                if (!this.drivers_details[index].spouse_is_driver) {
                    this.drivers_details[index].wife_licensing_status = null;
                    this.drivers_details[index].wife_license_status = null;
                    this.drivers_details[index].wife_sr_22 = null;
                }
                this.forceUpdate();
            },

            removeDriver(index) {
                this.drivers_details.splice(index, 1);
                this.no_of_drivers = this.drivers_details.length;
            },

            getAge(age) {
                let date = moment(age, 'MM/DD/YYYY');
                return moment().diff(date, 'years');
            },

            ageIsValid(dob)  {
                return this.getAge(dob) < 97 && this.getAge(dob) > 1;
            },

            licensingChanged(index) {
                this.drivers_details[index].sr_22 = null;
                this.drivers_details[index].license_status = null;
                this.drivers_details[index].license_no = null;

                if (this.drivers_details[index].licensing_status === 'california') {
                    this.drivers_details[index].good_driver = true;
                } else {
                    this.drivers_details[index].good_driver = null;
                }

                this.forceUpdate()
            },

            showDriverLicenseSection(i) {
                let driver = this.drivers_details[i];
                if (driver.licensing_status === 'california' || driver.licensing_status === 'other_state') {
                    if (driver.license_status !== null) {
                        return true;
                    }

                } else {
                    if (driver.licensing_status !== null) {
                        return true;
                    }
                }

                return false;
            },

            continueClicked() {
                let errorExists = false;

                $('body').find('input,select').each(function(i, el) {
                    if (errorExists) { return false; }

                    if ($(el).attr('id') !== 'no-of-drivers' && $(el).attr('name') !== 'zip') {
                        let title = $(el).data('title');
                        let driverNo = $(el).data('index') + 1;

                        if (!$(el).val()) {
                            errorExists = true;

                            swal({
                                title: "Empty Value!",
                                text: title + ' is not selected for Driver ' + driverNo + '. \n Please make sure all values are selected!',
                                icon: "error"
                            });
                        } else {
                            if (title === 'Birth Date' || title === "Spouse's Birth Date") {
                                let date = moment($(el).val(), "MM-DD-YYYY");
                                let age = moment().diff(date, 'years');
                                console.log(age);
                                if (age < 19 || age > 97) {
                                    swal({
                                        title: "Age Restriction!",
                                        text: title + ' of Driver ' + driverNo + ' should be between 19 and 97.',
                                        icon: "error"
                                    });

                                    errorExists = true;
                                }
                            }
                        }

                        if (title === 'First Name' || title === 'Last Name' || title === "Spouse's First Name" || title === "Spouse's Last Name") {
                            if ( /\d/.test($(el).val()) ) {
                                errorExists = true;
                                swal('Wrong Name Format', title + ' of Driver ' + driverNo + ' you have provided is not in valid format', 'info');
                            }
                        }
                    }
                });

                if (!errorExists) {
                    this.moveNextStep()
                }
            },

            licenseNoCheck(index, is_wife, e) {
                let field = is_wife ? 'wife_licensing_status' : 'licensing_status';
                if (this.drivers_details[index][field] !== 'california') {
                    return;
                }

                let value = e.target.value;
                let unicode=e.keyCode? e.keyCode : e.charCode;

                if (value.length === 0) {
                    if (!(unicode >= 65 && unicode <= 90)) {
                        e.preventDefault();
                    }
                } else if (unicode === 8 || unicode === 46 || unicode === 37 || unicode === 38 || unicode === 39 || unicode === 40) {
                    return;
                } else if (value.length > 7) {
                    e.preventDefault();
                } else if (!this.keyCodeIsNumeric(unicode)) {
                    if (unicode !== 8 && unicode !== 46) {
                        e.preventDefault();
                    }
                }
            },

            keyCodeIsNumeric(keycode) {
                return ((keycode >= 48 && keycode <= 57) || (keycode >= 96 && keycode <= 105))
            },

            moveNextStep() {
                EventBus.$emit('change_step', 3);
            }
        }
    }
</script>

<style>
    .driver-box {
        border: 1px solid green;
        border-radius: 2px;
    }
</style>