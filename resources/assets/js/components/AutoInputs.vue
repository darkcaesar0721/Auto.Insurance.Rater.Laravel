<template>
    <div>
        <div class="steps-form-2 mb-5">
            <div class="steps-row-2 setup-panel-2 d-flex justify-content-between">
                <div class="steps-step-2">
                    <a class="btn btn-amber btn-circle-2 waves-effect ml-0" :class="getStepsClass(1)" data-toggle="tooltip" data-placement="bottom" title="Vehicle" @click="goToStep(1)">
                        <i class="fa fa-car"></i>
                    </a>
                </div>
                <div class="steps-step-2">
                    <a class="btn btn-blue-grey btn-circle-2 waves-effect" :class="getStepsClass(2)" data-toggle="tooltip" data-placement="bottom" title="Driver" @click="goToStep(2)">
                        <i class="fa fa-id-card" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="steps-step-2">
                    <a class="btn btn-blue-grey btn-circle-2 waves-effect" :class="getStepsClass(3)" data-toggle="tooltip" data-placement="bottom" title="Verify">
                        <i class="fa fa-check"></i>
                    </a>
                </div>
                <div class="steps-step-2">
                    <a class="btn btn-blue-grey btn-circle-2 waves-effect mr-0" data-toggle="tooltip" data-placement="bottom" title="Buy">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>

        <transition name="fade" mode="out-in" v-cloak>
            <coverage-selection key="coverage" :coverage-selection="coverageSelection" v-if="step === 0"></coverage-selection>
            <vehicles-info-panel key="vehicle" :root-vehicles-details="rootVehiclesDetails" v-if="step === 1"></vehicles-info-panel>
            <driver-info key="driver" :root-drivers-details="rootDriversDetails" v-if="step === 2"></driver-info>
            <verify-panel
                    key="verify"
                    :drivers-details="rootDriversDetails"
                    :vehicles-details="rootVehiclesDetails"
                    :selected-coverage="coverageTypes[coverageSelection]"
                    :zip="zip"
                    v-if="step === 3"
            ></verify-panel>
            <auto-loading v-if="step === 'loading'"></auto-loading>
        </transition>
    </div>
</template>

<script>
    import CoverageSelection from './CoverageSelection'
    import VehiclesInfoPanel from './VehiclesInfoPanel'
    import DriverInfo from './DriverInfo'
    import VerifyPanel from './VerifyPanel'
    import AutoLoading from './AutoLoading'

    export default {
        props: ['coverageSelection', 'coverageTypes', 'rootVehiclesDetails', 'rootDriversDetails',  'step', 'latestStep', 'zip'],

        components: {
            CoverageSelection, VehiclesInfoPanel, DriverInfo, VerifyPanel, AutoLoading
        },

        mounted() {
            $('[data-toggle="tooltip"]').tooltip({ trigger: 'manual'})
        },

        methods: {
            getStepsClass(step, e) {
                this.$nextTick(() => {
                    $(".steps-step-2 > :not('.active')").tooltip('hide');
                });
                if (step === this.step) {
                    this.$nextTick(() => {
                        $(".steps-step-2 > :not('.active')").tooltip('hide');
                        $('.steps-step-2 > .active').tooltip('show');
                    });
                    return 'active';
                }
            },

            goToStep(step) {
                if (step <= this.latestStep) {
                    this.$parent.changeStep(step);
                }
            }
        }
    }
</script>


<style>
    /* Multi-step Form */

    .steps-form-2 {
        display: table;
        width: 100%;
        position: relative;
    }
    .steps-form-2 .steps-row-2 {
        display: table-row;
    }
    .steps-form-2 .steps-row-2:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 100%;
        height: 2px;
        background-color: #167f40;
    }
    .steps-form-2 .steps-row-2 .steps-step-2 {
        display: table-cell;
        text-align: center;
        position: relative;
    }
    .steps-form-2 .steps-row-2 .steps-step-2 p {
        margin-top: 0.5rem;
    }
    .steps-form-2 .steps-row-2 .steps-step-2 button[disabled] {
        opacity: 1 !important;
        filter: alpha(opacity=100) !important;
    }
    .steps-form-2 .steps-row-2 .steps-step-2 .btn-circle-2 {
        width: 70px;
        height: 70px;
        border: 2px solid #167f40;
        background-color: white !important;
        color: #167f40 !important;
        border-radius: 50%;
        padding: 22px 18px 15px 18px;
        margin-top: -22px;
    }
    .steps-form-2 .steps-row-2 .steps-step-2 .btn-circle-2.active {
        border: 2px solid #10d410;
        color: #2fbb2f !important;
        background-color: white !important;
    }
    .steps-form-2 .steps-row-2 .steps-step-2 .btn-circle-2 .fa {
        font-size: 1.7rem;
    }
</style>