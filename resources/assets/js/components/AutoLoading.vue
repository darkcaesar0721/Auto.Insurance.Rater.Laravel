<template>
    <div>
        <div class="col-12 text-center" style="height: 230px;">
            <div class="spinner mx-auto"></div>
            <transition name="fade" mode="out-in" v-cloak>
                    <img src="/images/third-party-logos/foremost.svg" key="foremost" class="carrier-logo foremost" v-if="current_logo === 'foremost'"/>
                    <img src="/images/third-party-logos/progressive.svg" key="progressive" class="carrier-logo progressive" v-else-if="current_logo === 'progressive'"/>
                    <img src="/images/third-party-logos/american-family.svg" key="american-family" class="carrier-logo american-family" v-else-if="current_logo === 'american-family'"/>
                    <img src="/images/third-party-logos/mercury.svg" key="mercury" class="carrier-logo mercury" v-else-if="current_logo === 'mercury'"/>
                    <img src="/images/third-party-logos/all-state.svg" key="all-state" class="carrier-logo all-state" v-else-if="current_logo === 'all-state'"/>
                    <img src="/images/third-party-logos/travelers.svg" key="travelers" class="carrier-logo travelers" v-else-if="current_logo === 'travelers'"/>
                    <img src="/images/third-party-logos/infinity.svg" key="infinity" class="carrier-logo infinity" v-else-if="current_logo === 'infinity'"/>
                    <img src="/images/third-party-logos/farmers.svg" key="farmers" class="carrier-logo farmers" v-else-if="current_logo === 'farmers'"/>
                    <img src="/images/third-party-logos/nationwide.svg" key="nationwide" class="carrier-logo nationwide" v-else-if="current_logo === 'nationwide'"/>
                    <img src="/images/third-party-logos/metlife.svg" key="metlife" class="carrier-logo metlife" v-else-if="current_logo === 'metlife'"/>
                    <img src="/images/third-party-logos/aaa.svg" key="aaa" class="carrier-logo aaa" v-else-if="current_logo === 'aaa'"/>

            </transition>

            <div class="col-12 position-absolute" style="bottom: 0;">
                <h3 class="loading-text">{{ loadingText }}</h3>
            </div>
        </div>
    </div>
</template>

<script>
    import EventBus from './event_bus'

    export default {
        data() {
            return {
                logos: [
                    'foremost', 'progressive', 'american-family', 'mercury', 'all-state', 'travelers', 'infinity', 'farmers',
                    'nationwide', 'metlife', 'aaa'
                ],
                current_logo: "foremost",

                loadingText: "Sending"
            }
        },

        watch: {
            loadingText(val) {
                if (val === 'Evaluating') {
                    setTimeout(() => {
                        EventBus.$emit('thinking-is-done');
                    }, 5000);
                }
            }
        },

        mounted() {
            setInterval(() => {
                this.addDots();
            }, 500);

            setInterval(() => {
                this.changeLoadingText();
            }, 4000);

            setInterval(() => {
                this.changeImage();
            }, 2000);
        },

        methods: {
            addDots() {
                let dotsCount = this.loadingText.split('.').length;

                if (dotsCount < 4) {
                    this.loadingText += '.';
                } else {
                    this.loadingText = this.loadingText.replace(/\./g, '');
                }
            },

            changeLoadingText() {
                let cases = ['Sending', "Receiving", "Underwriting", 'Evaluating'];
                let index = cases.indexOf(this.loadingText);

                if (typeof cases[index+1] !== 'undefined') {
                    this.loadingText = cases[index+1];
                }
            },

            changeImage() {
                let index = this.logos.indexOf(this.current_logo);

                if (typeof this.logos[index+1] !== 'undefined') {
                    this.current_logo = this.logos[index+1];
                } else {
                    this.current_logo = this.logos[0];
                }

            }
        }
    }
</script>

<style>
    .spinner{
        width: 150px;
        height: 150px;

        border: 2px solid #f3f3f3;
        border-top:3px solid #0e8040;
        border-radius: 100%;

        animation: spin 1s infinite linear;
    }

    @keyframes spin {
        from{
            transform: rotate(0deg);
        }to{
             transform: rotate(360deg);
         }
    }

    .loading-text {
        position: relative;
        right: 20px;
    }


    /*
    Logos
     */
    .carrier-logo {
        position: relative;
    }

    .carrier-logo.foremost {
        top: -90px;
        height: 29px;
    }

    .carrier-logo.progressive {
        top: -87px;
        height: 17px;
    }

    .carrier-logo.american-family {
        height: 48px;
        top: -105px;
    }

    .carrier-logo.mercury {
        height: 75px;
        top: -116px;
    }

    .carrier-logo.all-state {
        height: 77px;
        top: -116px;
    }

    .carrier-logo.travelers {
        height: 27px;
        top: -92px;
    }

    .carrier-logo.infinity {
        height: 24px;
        top: -89px;
    }

    .carrier-logo.farmers {
        height: 66px;
        top: -113px;
    }

    .carrier-logo.nationwide {
        height: 28px;
        top: -90px;
    }

    .carrier-logo.metlife {
        height: 27px;
        top: -92px;
    }

    .carrier-logo.aaa {
        height: 67px;
        top: -103px;
    }
</style>