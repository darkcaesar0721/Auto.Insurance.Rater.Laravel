<template>
    <div class="col-12">
        <form class="form-inline justify-content-center">
            <label class="my-1 mr-2" for="no-of-vehicles">Number of Vehicles</label>
            <select class="custom-select my-1 mr-sm-2" id="no-of-vehicles" v-model.number="no_of_vehicles">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <img class="number-arrow" src="/images/arrow.svg" v-if="no_of_vehicles === 0" />
        </form>

        <div v-for="(_, index) in vehicles_details" class="vehicle-box p-3 my-2" :key="index">
            <i class="fa fa-car mr-1"></i> <strong>Vehicle {{ index+1 }}</strong>
            <i class="fa fa-remove pull-right remove-icon pointer" @click="removeVehicle(index)"></i>

            <div class="row justify-content-center" v-if="vehicles_details[index].isLoading">
                <div class="lds-ellipsis">
                    <div></div><div></div><div></div><div></div>
                </div>
            </div>

            <div v-else>
                <div v-if="vehicles_details[index].has_vin">
                    <form class="form-inline mt-1">
                        <label>VIN Number:</label>
                        <input type="text" class="form-control flex-grow-1 mx-2" placeholder="(Car Serial Number) Most Accurate Price" v-model="vehicles_details[index].vin_no">

                        <button type="button" class="btn btn-success" @click="fetchVinDetails(index)">VIN Find</button>
                        <i class="fa fa-question-circle green-question-mark pointer ml-1" @click="showVinModal"></i>
                    </form>

                    <hr>

                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-outline-success" @click="noVinClicked(index)">Don't have the VIN?</button>
                    </div>
                </div>

                <div v-if="!vehicles_details[index].has_vin">
                    <div class="form-group row mt-1">
                        <label class="col-4 col-form-label text-right">Year</label>
                        <h4 class="col-7 col-sm-6 mb-0 h-100 align-middle pt-1" v-if="vehicles_details[index].vin_no">
                            {{ vehicles_details[index].year }}
                        </h4>
                        <select class="custom-select col-7 col-sm-6" @change="yearSelected(index, $event)" v-else>
                            <option selected value="null">Select Year</option>
                            <option v-for="year in years"
                                    :value="year"
                                    :key="year"
                                    :selected="year === vehicles_details[index].year"
                            >
                                {{ year }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group row mt-1" v-if="vehicles_details[index].makeOptions || vehicles_details[index].vin_no">
                        <label class="col-4 col-form-label text-right">Make</label>

                        <h4 class="col-7 col-sm-6 mb-0 h-100 align-middle pt-1" v-if="vehicles_details[index].vin_no">
                            {{ vehicles_details[index].make }}
                        </h4>
                        <select class="custom-select col-7 col-sm-6" @change="makeSelected(index, $event)" v-else>
                            <option selected value="null">Select Make</option>
                            <option v-for="make in vehicles_details[index].makeOptions"
                                    :value="make.id"
                                    :key="make.id"
                                    :selected="make.id === vehicles_details[index].make"
                            >
                                {{ make.name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group row mt-1" v-if="vehicles_details[index].modelOptions || vehicles_details[index].vin_no">
                        <label class="col-4 col-form-label text-right">Model</label>

                        <h4 class="col-7 col-sm-6 mb-0 h-100 align-middle pt-1" v-if="vehicles_details[index].vin_no">
                            {{ vehicles_details[index].model }}
                        </h4>
                        <select class="custom-select col-7 col-sm-6" @change="modelSelected(index, $event)" v-else>
                            <option selected value="null">Select Model</option>
                            <option v-for="model in vehicles_details[index].modelOptions"
                                    :value="model.id"
                                    :key="model.id"
                                    :selected="model.id === vehicles_details[index].model"
                            >
                                {{ model.name }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group row mt-1" v-if="vehicles_details[index].subModelOptions || vehicles_details[index].vin_no">
                        <label class="col-4 col-form-label text-right">Sub-Model</label>

                        <h4 class="col-7 col-sm-6 mb-0 h-100 align-middle pt-1" v-if="vehicles_details[index].vin_no">
                            {{ vehicles_details[index].sub_model }}
                        </h4>
                        <select class="custom-select col-7 col-sm-6" @change="subModelSelected(index, $event)" v-else>
                            <option selected value="null">Select Sub-Model</option>
                            <option v-for="subModel in vehicles_details[index].subModelOptions"
                                    :value="subModel.id"
                                    :key="subModel.id"
                            >
                                {{ subModel.name }}
                            </option>
                        </select>
                    </div>

                    <div v-if="vehicles_details[index].sub_model">
                        <hr />
                        <div class="col-12 text-center">
                            <label class="font-weight-bold">
                                COMPREHENSIVE/COLLISION COVERAGE
                            </label>
                            <i class="fa fa-question-circle green-question-mark pointer ml-2" @click="showCollisionModal"></i>
                        </div>

                        <div class="form-group row mt-1">
                            <label class="col-4 col-form-label text-right">Coverage</label>
                            <select class="custom-select col-7 col-sm-6" v-model="vehicles_details[index].coverage" @change="coverageChanged()">
                                <option selected value="none">No Coverage (Liability Only)</option>
                                <option selected value="250">$250/$250</option>
                                <option selected value="500">$500/$500</option>
                                <option selected value="1000">$1000/$1000</option>
                            </select>
                        </div>

                        <div v-if="vehicles_details[index].coverage !== 'none'">
                            <hr/>

                            <div class="col-12 text-center">
                                <label class="font-weight-bold">
                                    DISCOUNTS <span class="sale">-5%</span>
                                </label>
                            </div>
                            <div class="row">
                                <div class="col-auto ml-auto mr-4">
                                    <div class="font-weight-bold mx-auto">
                                        Usage:
                                    </div>
                                    <div class="mx-auto">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" :id="'usageRadio1' + index" :name="'usage_' + index" class="custom-control-input" v-model="vehicles_details[index].usage" value="commute">
                                            <label class="custom-control-label" :for="'usageRadio1' + index">Commute</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" :id="'usageRadio2' + index" :name="'usage_' + index" class="custom-control-input" v-model="vehicles_details[index].usage" value="pleasure">
                                            <label class="custom-control-label" :for="'usageRadio2' + index">Pleasure</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" :id="'usageRadio3' + index" :name="'usage_' + index" class="custom-control-input" v-model="vehicles_details[index].usage" value="business">
                                            <label class="custom-control-label" :for="'usageRadio3' + index">Business</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto mr-auto">
                                    <div class="font-weight-bold mx-auto">
                                        Alarm:
                                    </div>
                                    <div class="mx-auto">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" :id="'alarmRadio1' + index" :name="'alarm_' + index" class="custom-control-input" v-model="vehicles_details[index].alarm" value="none">
                                            <label class="custom-control-label" :for="'alarmRadio1' + index">None</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" :id="'alarmRadio2' + index" :name="'alarm_' + index" class="custom-control-input" v-model="vehicles_details[index].alarm" value="passive">
                                            <label class="custom-control-label" :for="'alarmRadio2' + index">Passive</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" :id="'alarmRadio3' + index" :name="'alarm_' + index" class="custom-control-input" v-model="vehicles_details[index].alarm" value="active">
                                            <label class="custom-control-label" :for="'alarmRadio3' + index">Active</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" :id="'alarmRadio4' + index" :name="'alarm_' + index" class="custom-control-input" v-model="vehicles_details[index].alarm" value="tracking_device">
                                            <label class="custom-control-label" :for="'alarmRadio4' + index">Tracking Device</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row py-4 justify-content-center" v-if="no_of_vehicles">
            <div class="col-5">
                <button class="btn btn-primary btn-block" @click="continueClicked()">Continue</button>
            </div>
        </div>

        <div class="modal fade" id="coverageModal" tabindex="-1" role="dialog" aria-labelledby="coverageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg h-100 d-flex flex-column justify-content-center my-0" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Comprehensive & Collision <i class="fa fa-question-circle green-question-mark ml-2"></i></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideCollisionModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="!showing_video">
                        <div class="col-12">
                            <p>
                                <strong>Comprehensive Deductible</strong><br/>
                                Comprehensive coverage is optional. The deductible amount you see in your quote refers to the portion of the
                                claim that you are responsible for. The higher the deductible, the lower the premium.
                                <br/><br/>

                                <strong>Collision Deductible?</strong>
                                Collision coverage is optional. The deductible amount you see in your quote refers to the portion of the claim
                                that you are responsible for. The higher the deductible, the lower the premium.
                                <br/>
                            </p>
                        </div>
                        <div class="col-12 mb-2">
                            <a href="/Comprehensive-And-Collision" target="_blank">Need Better understanding? Click Here</a>
                        </div>
                        <div class="col-12">
                            <a class="pointer" href.prevent="/" @click="showing_video = true">Show me a video? Click Here</a>
                        </div>
                    </div>

                    <div class="modal-body" v-else>
                        <div class="col-12 text-center">
                            <iframe id="coverage-player" width="560" height="315" src="https://embed.wix.com/video?instanceId=7812e227-8952-47fa-9599-313411d50079&biToken=1244454b-841e-0943-0638-2ccf890eaccf&pathToPage=auto-insurance-videos&channelId=aeff734344554ea79c7f3b4484dd423d&videoId=ff264af866174600a80f4edddba2e00a&compId=comp-jkiedqlg&sitePageId=goeft" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="vinModal" tabindex="-1" role="dialog" aria-labelledby="vinModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg h-100 d-flex flex-column justify-content-center my-0" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vinModalLabel">What’s a Vehicle Identification Number (VIN) <i class="fa fa-question-circle green-question-mark ml-2"></i></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideVinModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <p>
                                A vehicle identification numbers (VIN) is a unique code given to each on-road vehicle in
                                the United States. Since 1981, each new car has been given a standardized 17-digit
                                code, which includes a serial number. Older cars may have VINs too, although they will
                                not follow the standardized formula. A VIN lets you unlock vital information about the
                                vehicle and its history.
                            </p>

                            <p>
                                Note: If Rater dose not find VIN, Click the button bellow VIN and complete your rate.
                            </p>

                            <p>
                                Keep in mind system will reject all large commercial Vehicles, Motorcycles or Boat Haul
                                VIN.
                            </p>
                        </div>
                        <div class="col-12 mb-2">
                            <a href="/VIN" target="_blank">Need Better understanding? Click Here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import EventBus from './event_bus'

    export default {
        props: ['rootVehiclesDetails'],

        data() {
            return {
                no_of_vehicles: this.rootVehiclesDetails.length,
                vehicles_details: this.rootVehiclesDetails,

                showing_video: false,

                years: []
            }
        },

        mounted() {
            const vm = this;

            axios.get('/api/auto/years').then(response => {
                vm.years = response.data.years;
            });


            $('#coverageModal').on('hidden.bs.modal', function () {
                vm.hideCollisionModal();
            })

            if (this.no_of_vehicles == 0) {
                this.no_of_vehicles = 1; 
            }
        },

        watch: {
            no_of_vehicles(val) {
                for (let i = 0; i < val; i++) {
                    if (!this.vehicles_details[i]) {
                        this.vehicles_details[i] = {
                            has_vin: true,
                            vin_no: null,
                            year: null,
                            make: null,
                            model: null,
                            sub_model: null,
                            coverage: "none",

                            makeOptions: null,
                            modelOptions: null,
                            subModelOptions: null,

                            isLoading: false,
                        }
                    }
                }

                let keys = Object.keys(this.vehicles_details);

                for (let key of keys) {
                    if (key > this.no_of_vehicles - 1) {
                        this.vehicles_details.pop()
                    }
                }

                EventBus.$emit('vehicles_details_changed', this.vehicles_details);
            }
        },

        methods: {
            removeVehicle(index) {
                this.vehicles_details.splice(index, 1);
                this.no_of_vehicles = this.vehicles_details.length;
            },

            noVinClicked(index) {
                // this.vehicles_details[index].has_vin = false;
                const newVehicle = this.vehicles_details[index];
                newVehicle.vin_no = null;
                newVehicle.has_vin = false;
                this.$set(this.vehicles_details, index, newVehicle)
            },

            fetchVinDetails(index) {
                let vinNo = this.vehicles_details[index].vin_no;
                if (!vinNo) {
                    swal({
                        title: "Empty VIN Field!",
                        text: "VIN Field is empty. \n Please fill it and try again!",
                        icon: "error"
                    });

                    return;
                }

                vinNo = vinNo.replace(/ /g, '');

                if (vinNo.length !== 17) {
                    swal({
                        title: "Incorrect VIN!",
                        text: "Incorrect VIN or vehicle is older than 1981! Please enter vehicle manually",
                        icon: "error"
                    });

                    return;
                }

                // vinNo = "WMWSV3C56CTY25320";

                const vm = this;

                const newVehicle = this.vehicles_details[index];
                newVehicle.isLoading = true;
                this.$set(this.vehicles_details, index, newVehicle);

                $.ajax({
                    url: "https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValues/" + vinNo,
                    type: "get",
                    data: { format: "json"},
                    dataType: "json",
                    success: function(response)
                    {
                        let result = response.Results[0];

                        if (result["VehicleType"] === 'MOTORCYCLE' || !result['ErrorCode'].includes('0')) {
                            if (result["VehicleType"] === 'MOTORCYCLE') {
                                swal({
                                    title: "Motorcycle VIN Entered!",
                                    text: "You've entered a Motorcycle VIN. \n 'Auto Rating' is only for automobiles. \n Please try another again.",
                                    icon: "error"
                                });
                            } else {
                                swal({
                                    title: "Incorrect VIN!",
                                    text: "We could not match any vehicles with given VIN. \n Please try again or add vehicle manually.",
                                    icon: "info"
                                });
                            }

                            let newVehicle = vm.vehicles_details[index];
                            newVehicle.isLoading = false;
                            vm.$set(vm.vehicles_details, index, newVehicle);
                            return;
                        }

                        let newVehicle = vm.vehicles_details[index];
                        newVehicle.has_vin = false;
                        newVehicle.year = result['ModelYear'];
                        newVehicle.make = result['Make'];
                        newVehicle.model = result['Model'];
                        newVehicle.sub_model = result['Series'] || result['BodyClass'];
                        newVehicle.isLoading = false;
                        vm.$set(vm.vehicles_details, index, newVehicle);

                        // let obj = {
                        //     year: result['ModelYear'],
                        //     make: result['Model'].match(/scion/gi) ? "Scion" : result['Make'],
                        //     model: result['Make'].match(/mercedes/gi) ? result['Series'] : result['Model']
                        // };

                        // axios.post('/api/auto/vin-details', obj).then(response => {
                        //     const newVehicle = vm.vehicles_details[index];
                        //     newVehicle.has_vin = false;
                        //     newVehicle.makeOptions = response.data.makeOptions;
                        //     newVehicle.modelOptions = response.data.modelOptions;
                        //     newVehicle.subModelOptions = response.data.subModelOptions;
                        //
                        //     newVehicle.year = response.data.year;
                        //     newVehicle.make = response.data.make;
                        //     newVehicle.model = response.data.model;
                        //     newVehicle.isLoading = false;
                        //     vm.$set(vm.vehicles_details, index, newVehicle);
                        //
                        // }).catch(error => {
                        //     console.log(error);
                        //
                        //     swal({
                        //         title: "No Match Found!",
                        //         text: 'We could not match the given VIN. \n Please enter your vehicle manually!',
                        //         icon: "error"
                        //     });
                        //
                        //     const newVehicle = vm.vehicles_details[index];
                        //     newVehicle.has_vin = false;
                        //     newVehicle.isLoading = false;
                        //     vm.$set(vm.vehicles_details, index, newVehicle);
                        // })
                    },
                    error: function(xhr, ajaxOptions, thrownError)
                    {
                        swal({
                            title: "Something went wrong!",
                            text: "We could not match any vehicles with given VIN. \n Please add vehicle manually.",
                            icon: "info"
                        });
                    }
                });
            },

            showLoading(index) {
                const newVehicle = this.vehicles_details[index];
                newVehicle.isLoading = true;
                this.$set(this.vehicles_details, index, newVehicle);
            },

            hideLoading(index) {
                const newVehicle = this.vehicles_details[index];
                newVehicle.isLoading = false;
                this.$set(this.vehicles_details, index, newVehicle);
            },

            yearSelected(index, event) {
                let year = event.target.value;

                if (year === 'null') { return false; }

                const newVehicle = this.vehicles_details[index];
                newVehicle.year = year;

                const vm = this;
                this.showLoading(index);

                axios.get('/api/auto/years/' + year + '/makes').then(response => {
                    newVehicle.make = null;
                    newVehicle.model = null;
                    newVehicle.sub_model = null;
                    newVehicle.makeOptions = response.data.makes;
                    newVehicle.modelOptions = null;
                    newVehicle.subModelOptions = null;
                    vm.$set(vm.vehicles_details, index, newVehicle);

                    vm.hideLoading(index)
                })
            },

            makeSelected(index, event) {
                let make = event.target.value;

                if (make === 'null') { return false; }

                const newVehicle = this.vehicles_details[index];

                if (newVehicle.make === make) {
                    return false;
                }

                const vm = this;
                this.showLoading(index);

                newVehicle.make = parseInt(make);

                axios.get('/api/auto/makes/' + make + '/models').then(response => {
                    newVehicle.model = null;
                    newVehicle.sub_model = null;
                    newVehicle.modelOptions = response.data.models;
                    newVehicle.subModelOptions = null;
                    vm.$set(vm.vehicles_details, index, newVehicle);

                    vm.hideLoading(index)
                })
            },

            modelSelected(index, event) {
                let model = event.target.value;

                if (model === 'null') { return false; }

                const newVehicle = this.vehicles_details[index];

                if (newVehicle.model === model) {
                    return false;
                }

                const vm = this;
                this.showLoading(index);

                newVehicle.model = parseInt(model);

                axios.get('/api/auto/models/' + model + '/sub-models').then(response => {
                    newVehicle.sub_model = null;
                    newVehicle.subModelOptions = response.data.subModels;
                    vm.$set(vm.vehicles_details, index, newVehicle);
                    vm.hideLoading(index)
                })
            },

            coverageChanged() {
                EventBus.$emit('vehicles_details_changed', this.vehicles_details);
            },

            subModelSelected(index, event) {
                let subModel = event.target.value;

                if (subModel === 'null') { return false; }

                const newVehicle = this.vehicles_details[index];
                newVehicle.sub_model = parseInt(subModel);
                this.$set(this.vehicles_details, index, newVehicle);
            },

            showCollisionModal() {
                $('body').find('#coverageModal').modal('show');
            },

            hideCollisionModal() {
                let $body = $('body');
                $body.find('#coverageModal').modal('hide');

                let player = $body.find('#coverage-player');
                player.attr('src', player.attr('src'));

                this.showing_video = false;
            },

            showVinModal() {
                $('body').find('#vinModal').modal('show');
            },

            hideVinModal() {
                let $body = $('body');
                $body.find('#vinModal').modal('hide');
            },

            continueClicked() {
                let fields = ['year', 'make', 'model', 'sub_model'];
                let errorExists = false;

                for (let i = 0; i < this.vehicles_details.length; i++) {
                    fields.forEach(valKey => {
                        if (errorExists) { return false; }

                        if (this.vehicles_details[i][valKey] === 'null' || !this.vehicles_details[i][valKey]) {
                            let key = _.startCase(_.toLower(valKey));
                            swal({
                                title: "Empty Value!",
                                text: key + ' value is not selected for Vehicle ' + (i+1) + '. \n Please make sure all values are selected!',
                                icon: "error"
                            });

                            errorExists = true;
                        }
                    })
                }

                if (!errorExists) {
                    this.moveNextStep()
                }
            },

            moveNextStep() {
                EventBus.$emit('change_step', 2);
            }

        }
    }
</script>

<style scoped>
    .vehicle-box {
        border: 1px solid green;
        border-radius: 2px;
    }

    .remove-icon:hover {
        opacity: 0.7;
    }
    
    .sale {
        position: relative;
        right: -10px;
        top: -3px;
        font-weight: 500;
    }
</style>