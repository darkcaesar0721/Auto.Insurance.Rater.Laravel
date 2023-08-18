<template>
    <div class="col-12 zip-container mt-3 text-center py-3">
        <div class="row" v-for="(vehicle, i) in vehicles">
            <div class="col-auto my-auto">
                <i class="fa fa-car fa-2x"></i>
            </div>
            <div class="col-auto">
                <div class="row font-weight-bold">
                    {{ vehicle.year }} {{ getVehicleMake(vehicle) }}
                </div>

                <div class="row mt-2 font-weight-bold">
                    BI/PD:
                </div>
                <div class="row">
                    {{ selectedCoverage.injury }}
                </div>
                <div class="row">
                    {{ selectedCoverage.damage }}
                </div>

                <div class="row mt-2 font-weight-bold">
                    COMP/COLL:
                </div>
                <div class="row">
                    {{ getVehicleCoverage(vehicle) }}
                </div>
            </div>

            <!--<div class="col-2">-->
                <!--<div class="row mx-auto font-weight-bold">-->
                    <!--Coverage:-->
                <!--</div>-->
                <!--<div class="row mx-auto">-->
                    <!--{{ getVehicleCoverage(vehicle) }}-->
                <!--</div>-->
            <!--</div>-->

            <div class="col-12" v-if="i !== vehicles.length-1">
                <hr/>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['vehicles', 'coverageSelection', 'coverageTypes'],

        data() {
            return {
                selectedCoverage: this.coverageTypes[this.coverageSelection]
            }
        },

        mounted() {

        },

        methods: {
            getVehicleMake(vehicle) {
                if (typeof vehicle.make === 'string') {
                    return vehicle.make;
                }

                let makeName = vehicle.makeOptions.filter(obj => {
                    return obj.id === vehicle.make
                });

                return makeName[0].name
            },


            getVehicleCoverage(vehicle) {
                switch (vehicle.coverage) {
                    case 'none':
                        return "Liability Only";
                    case '250':
                    case '500':
                    case '1000':
                        return vehicle.coverage + '/' + vehicle.coverage;
                }
            },
        }
    }
</script>