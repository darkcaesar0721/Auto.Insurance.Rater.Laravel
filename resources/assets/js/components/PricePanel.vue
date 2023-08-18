<template>
    <div class="col-12 py-2 price-container">
        <div class="row col-12 h-100 position-absolute spinner-box" v-if="isThinking">
            <div class="lds-spinner mx-auto align-self-center">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
            </div>
        </div>

        <div class="col-12 my-1 text-center">
            <i class="fa fa-lock fa-2x align-middle mr-2"></i>
            <span class="align-middle">Secured Shopping Cart</span>
        </div>
        <hr class="my-2">

        <div class="row" :class="{'card-opaque': isThinking}">
            <div class="col-12 px-1 d-inline-flex">
                <div class="col">
                    <h5 class="font-w600 my-2">{{ coverage.name }}</h5>
                    <img :src="coverage.img" class="w-100"/>
                </div>

                <div class="col-auto pl-0 pr-2 my-auto">
                    <h3 class="display-2 my-2">
                        <span class="currency">$</span>{{ total_price }}<span class="period">/month</span>
                    </h3>
                </div>
            </div>

            <div class="row mt-2 px-3">
                <div class="col-12">
                    <p class="mb-0 font-weight-bold">BI/PD Limits:</p>
                    <p class="mb-0">{{ coverage.injury }}</p>
                    <p class="mb-0">{{ coverage.damage }}</p>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import EventBus from './event_bus'

    export default {
        props: ['coverageSelection', 'rootCoverageTypes'],

        data() {
            return {
                isThinking: false,

                coverage_types: this.rootCoverageTypes,

                total_price: this.rootCoverageTypes[this.coverageSelection].price
            }
        },

        watch: {
            coverageSelection() {
                this.isThinking = false;
            }
        },

        mounted() {
            const vm = this;

            // EventBus.$on('vehicles_details_changed', vehicles => {
            //     this.vehiclesCount = vehicles.length
            // });

            // EventBus.$on('coverage_changed', type => {
            //     vm.isThinking = true;
            // });

            EventBus.$on('price_changed', price => {
                vm.isThinking = true;

                setTimeout(() => {
                    vm.total_price = price,
                    vm.isThinking = false;
                }, 1000)

            });
        },

        computed: {
            coverage() {
                return this.coverage_types[this.coverageSelection]
            },

            calculatedPrice() {
                // if (this.vehiclesCount === 0) {
                //     return this.coverage.price + this.zipPrice
                // }
                //
                //
                // switch (this.vehiclesCount) {
                //     case 1:
                //         return 17;
                //     case 2:
                //         return 34;
                //     case 3:
                //         return 51;
                //     case 4:
                //         return 68;
                //     case 5:
                //         return 85;
                // }
            }
        }
    }
</script>

<style>
    .display-2 {
        font-size: 4rem;
        letter-spacing: -.3rem;
    }

    .display-2 .currency {
        font-size: 1.55rem;
        position: relative;
        font-weight: 400;
        top: -40px;
        letter-spacing: 1px;
    }

    .display-2 .period {
        font-size: 0.8rem;
        color: #b3b3b3;
        letter-spacing: 0;
        margin-left: 2px;
    }
</style>