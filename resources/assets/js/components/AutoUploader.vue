<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

    export default {
        props: ['driversCount', 'vehiclesCount', 'quoteId'],

        methods: {
            submitBtnClicked() {
                let driversFileCount = this.$refs.driversComponent.$refs.driversDropzone.dropzone.files.length;

                if (driversFileCount < this.driversCount) {
                    let diff = this.driversCount - driversFileCount;
                    let moreFilesTxt = diff === 1 ? 'file' : 'files';
                    swal('More Files Needed', diff + ' more ' + moreFilesTxt + ' needed for Drivers Licenses', 'error');
                    return;
                }

                let vehiclesRegistrationFileCount = this.$refs.vehiclesRegistrationComponent.$refs.vehiclesRegistrationDropzone.dropzone.files.length;

                if (vehiclesRegistrationFileCount < this.vehiclesCount) {
                    let diff = this.vehiclesCount - vehiclesRegistrationFileCount;
                    let moreFilesTxt = diff === 1 ? 'file' : 'files';
                    swal('More Files Needed', diff + ' more ' + moreFilesTxt + ' needed for Vehicles Registration', 'error');
                    return;
                }

                let vehiclesPhotosFileCount = this.$refs.vehiclesPhotosComponent.$refs.vehiclePhotosDropzone.dropzone.files.length;

                if (vehiclesPhotosFileCount < this.vehiclesCount * 2) {
                    let diff = (this.vehiclesCount * 2) - vehiclesPhotosFileCount;
                    let moreFilesTxt = diff === 1 ? 'file' : 'files';
                    swal('More Files Needed', diff + ' more ' + moreFilesTxt + ' needed for Vehicle Photos', 'error');
                    return;
                }

                window.location.replace("/auto/quote/" + this.quoteId + "/upload-success");
            }
        }
    }

    /*
    Driver Licenses Dropzone
     */
    Vue.component('drivers-dropzone', {
        props: ['quoteId'],

        components: {
            vueDropzone: vue2Dropzone
        },

        data() {
            return {
                options: {
                    url: '/api/auto/quote/' + this.quoteId + '/upload/drivers-licenses',
                    thumbnailWidth: 150,
                    maxFilesize: 1,
                    dictDefaultMessage: "Upload Driver Licenses for All Drivers",
                    addRemoveLinks: true,
                    acceptedFiles: 'image/*,application/pdf'
                },

                files: {},
            }

            // this.$refs.myUniqueID.dropzone.files.length
        },

        template: `
            <vue-dropzone ref="driversDropzone"
                          :options="options"
                          id="driversDropzone"
                          @vdropzone-sending="vfileSending"
                          @vdropzone-success="vfileSuccess"
                          @vdropzone-removed-file="vfileRemoved">
            </vue-dropzone>
        `,

        methods: {
            vfileSending(file, xhr, formData) {
                formData.append("_token", document.head.querySelector('meta[name="csrf-token"]').content);
            },

            vfileSuccess(file, response) {
                let originalName = file.name;
                this.files[originalName] = response.hash_name;
            },

            vfileRemoved(file, error, xhr) {
                let fileName = file.name;
                axios.post('/api/auto/quote/' + this.quoteId + '/upload/drivers-licenses/delete', {
                    filename: this.files[fileName]
                }).then(response => {

                }).catch(err => {

                })
            }
        }
    });

    /*
    Vehicles Registration Dropzone
     */
    Vue.component('vehicles-registration-dropzone', {
        props: ['quoteId'],

        components: {
            vueDropzone: vue2Dropzone
        },

        data() {
            return {
                options: {
                    url: '/api/auto/quote/' + this.quoteId + '/upload/vehicles-registration',
                    thumbnailWidth: 150,
                    maxFilesize: 1,
                    dictDefaultMessage: "Upload Vehicles Registrations For Each Vehicle",
                    addRemoveLinks: true,
                    acceptedFiles: 'image/*,application/pdf'
                },

                files: {},
            }

            // this.$refs.myUniqueID.dropzone.files.length
        },

        template: `
            <vue-dropzone ref="vehiclesRegistrationDropzone"
                          :options="options"
                          id="vehiclesRegistrationDropzone"
                          @vdropzone-sending="vfileSending"
                          @vdropzone-success="vfileSuccess"
                          @vdropzone-removed-file="vfileRemoved">
            </vue-dropzone>
        `,

        methods: {
            vfileSending(file, xhr, formData) {
                formData.append("_token", document.head.querySelector('meta[name="csrf-token"]').content);
            },

            vfileSuccess(file, response) {
                let originalName = file.name;
                this.files[originalName] = response.hash_name;
            },

            vfileRemoved(file, error, xhr) {
                let fileName = file.name;
                axios.post('/api/auto/quote/' + this.quoteId + '/upload/vehicles-registration/delete', {
                    filename: this.files[fileName]
                }).then(response => {

                }).catch(err => {

                })
            }
        }
    });

     /*
    Vehicle Photos Dropzone
     */
    Vue.component('vehicle-photos-dropzone', {
        props: ['quoteId'],

        components: {
            vueDropzone: vue2Dropzone
        },

        data() {
            return {
                options: {
                    url: '/api/auto/quote/' + this.quoteId + '/upload/vehicle-photos',
                    thumbnailWidth: 150,
                    maxFilesize: 1,
                    dictDefaultMessage: "Upload 2 Photos For Each Vehicle",
                    addRemoveLinks: true,
                    acceptedFiles: 'image/*,application/pdf'
                },

                files: {},
            }

            // this.$refs.myUniqueID.dropzone.files.length
        },

        template: `
            <vue-dropzone ref="vehiclePhotosDropzone"
                          :options="options"
                          id="vehiclePhotosDropzone"
                          @vdropzone-sending="vfileSending"
                          @vdropzone-success="vfileSuccess"
                          @vdropzone-removed-file="vfileRemoved">
            </vue-dropzone>
        `,

        methods: {
            vfileSending(file, xhr, formData) {
                formData.append("_token", document.head.querySelector('meta[name="csrf-token"]').content);
            },

            vfileSuccess(file, response) {
                let originalName = file.name;
                this.files[originalName] = response.hash_name;
            },

            vfileRemoved(file, error, xhr) {
                let fileName = file.name;
                axios.post('/api/auto/quote/' + this.quoteId + '/upload/vehicle-photos/delete', {
                    filename: this.files[fileName]
                }).then(response => {

                }).catch(err => {

                })
            }
        }
    })


</script>