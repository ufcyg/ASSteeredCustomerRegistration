import template from './as-steered-customer-registration-overview.html.twig';

const { Criteria } = Shopware.Data;

Shopware.Component.register('as-steered-registration-overview', {
    template,
    inject: [
        'repositoryFactory'
    ],
    data() {
        return {
            repository: null,
            invitations: null
        };
    },
    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },
    computed: {
        columns() {
            return [{
                property: 'targetMail',
                dataIndex: 'targetMail',
                label: this.$t('as-steered-customer-registration.general.columnTargetMail'),
                allowResize: true,
                primary: true
            }
            // ,{
            //     property: 'token',
            //     dataIndex: 'token',
            //     label: this.$t('as-steered-customer-registration.general.token'),
            //     allowResize: true
            // }
            ];
        }
    },
    created() {
        this.repository = this.repositoryFactory.create('synlab_steered_customer_registration_whitelist')
    
        this.repository
            .search(new Criteria(), Shopware.Context.api)
            .then((result) => {
                this.invitations = result;
            });
    },
    methods: {
        async onUploadsAdded() {
            await this.mediaService.runUploads(this.uploadTag);
            this.reloadList();
        },

        onUploadFinished({ targetId }) {
            this.uploads = this.uploads.filter((upload) => {
                return upload.id !== targetId;
            });
        },

        onUploadFailed({ targetId }) {
            this.uploads = this.uploads.filter((upload) => {
                return targetId !== upload.id;
            });
        }
    }
});