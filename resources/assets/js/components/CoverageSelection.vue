<template>
    <div>
        <div class="col-12 text-center mb-4">
            <h4 class="font-w600">Select Coverage Type</h4>
        </div>

        <div class="row">
            <!--<div class="col-12 align-self-center text-center position-absolute" v-if="isThinking">-->
                <!--<div class="lds-ellipsis">-->
                    <!--<div></div><div></div><div></div><div></div>-->
                <!--</div>-->
            <!--</div>-->
            <div class="col-md-4 text-center">
                <div class="col-12 coverage-box font-w600 py-3 pointer" :class="getClass('basic')" @click="changeCoverage('basic', $event)">
                    <h5 class="font-w600 mb-3">Basic</h5>
                    <i class="fa fa-question-circle question-mark" @click="showCoverageModal()"></i>
                    <img src="/images/basic.svg" class="w-100 mv-160px" />

                    <div class="coverage-box-infos text-left mt-3">
                        <p class="mb-0">BI/PD Limits:</p>
                        <p class="mb-0">$15.000 / $30.000</p>
                        <p class="mb-0">$5.000</p>

                    </div>

                    <div class="mt-2">
                        <small class="text-muted"><sup>*</sup>Minimum for California</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center py-3 py-sm-0">
                <div class="col-12 coverage-box font-w600 py-3 pointer h-100" :class="getClass('better')" @click="changeCoverage('better', $event)">
                    <h5 class="font-w600 mb-3">Better</h5>
                    <i class="fa fa-question-circle question-mark" @click="showCoverageModal()"></i>
                    <img src="/images/better.svg" class="w-100 mv-160px" />

                    <div class="coverage-box-infos text-left mt-3">
                        <p class="mb-0">BI/PD Limits:</p>
                        <p class="mb-0">$25.000 / $50.000</p>
                        <p class="mb-0">$25.000</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="col-12 coverage-box font-w600 py-3 pointer h-100" :class="getClass('best')"  @click="changeCoverage('best', $event)">
                    <h5 class="font-w600 mb-3">Best</h5>
                    <i class="fa fa-question-circle question-mark" @click="showCoverageModal()"></i>
                    <img src="/images/best.svg" class="w-100 mv-160px" />

                    <div class="coverage-box-infos text-left mt-3">
                        <p class="mb-0">BI/PD Limits:</p>
                        <p class="mb-0">$100.000 / $300.000</p>
                        <p class="mb-0">$50.000</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row py-4 justify-content-center">
            <div class="col-5">
                <button class="btn btn-primary btn-block" @click="changeStepTo(1)">Start</button>
            </div>
        </div>

        <div class="modal fade" id="coverageModal" tabindex="-1" role="dialog" aria-labelledby="coverageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg h-100 d-flex flex-column justify-content-center my-0" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Liability Bodily Injury - Property Damage <i class="fa fa-question-circle green-question-mark"></i></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideCoverageModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="!showing_video">
                        <div class="col-12">
                            <p>
                                Liability Bodily Injury - Property Damage coverage is a
                                mandatory coverage in most states. The limits shown within this
                                help text are only examples and may or may not be applicable to
                                your state; the values here are simply used to assist in defining
                                how the coverage is displayed and what each value means.
                            </p>
                        </div>
                        <div class="col-12 mb-2">
                            <a href="/Liability-Bodily-Injury-And-Property-Damage" target="_blank">Need Better understanding? Click Here</a>
                        </div>
                        <div class="col-12">
                            <a class="pointer" href.prevent="/" @click="showing_video = true">Show me a video? Click Here</a>
                        </div>
                    </div>

                    <div class="modal-body" v-else>
                        <div class="col-12 text-center">
                            <iframe id="coverage-player" width="560" height="315" src="https://embed.wix.com/video?instanceId=7812e227-8952-47fa-9599-313411d50079&biToken=1244454b-841e-0943-0638-2ccf890eaccf&pathToPage=auto-insurance-videos&channelId=aeff734344554ea79c7f3b4484dd423d&videoId=30b2ad0ba87b4a4a8694f9f48ee637c0&compId=comp-jkiedqlg&sitePageId=goeft" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import EventBus from './event_bus.js';

    export default {
        props: ['coverageSelection', 'zip'],

        data() {
            return {
                showing_video: false
                // isThinking: false
            }
        },

        mounted() {
            const vm = this;

            $('#coverageModal').on('hidden.bs.modal', function () {
                vm.hideCoverageModal();
            })
        },

        methods: {
            changeCoverage(type, e) {
                let arr = [... e.target.classList];
                if (arr.includes('question-mark')) {
                    return;
                }

                EventBus.$emit('coverage_changed', type);
            },

            getClass(type) {
                return type === this.coverageSelection ? 'active': 'card-opaque'
            },

            changeStepTo(step) {
                EventBus.$emit('change_step', step);
            },

            showCoverageModal() {
                $('body').find('#coverageModal').modal('show');
            },

            hideCoverageModal() {
                let $body = $('body');
                $body.find('#coverageModal').modal('hide');

                let player = $body.find('#coverage-player');
                player.attr('src', player.attr('src'));

                this.showing_video = false;
            }
        }
    }
</script>

<style>
    .question-mark {
        position: absolute;
        top: 5px;
        font-size: 28px;
        right: 7px;
        color: #167f40
    }

    .question-mark:hover {
        color: #1b9a4e
    }

    /* Coverage */
    .coverage-box {
        border: 1px solid #979797;
        border-radius: 3px;
    }

    .coverage-box:hover, .coverage-box.active {
        border: 1px solid #51e051;
        box-shadow: 0 0 6px rgb(123, 255, 93);
        opacity: 1;
    }

    .coverage-box.no-hover:hover {
        border: 1px solid #979797;
        box-shadow: none !important;
        opacity: 0.5 !important;
        cursor: default !important;
    }

    .mv-160px {
        max-width: 160px;
    }
</style>