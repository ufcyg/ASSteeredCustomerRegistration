import template from './as-steered-customer-registration-invite.html.twig';

const { Component, Mixin } = Shopware;

Component.register('as-steered-registration-invite', {
    template,

    inject: [
        'repositoryFactory'
    ],

    mixins: [
        Mixin.getByName('notification')
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            invite: null,
            isLoading: false,
            processSuccess: false,
            repository: null
        };
    },

    computed: {
        options() {
            return [
                { value: 'absolute', name: this.$t('swag-bundle.detail.absoluteText') },
                { value: 'percentage', name: this.$t('swag-bundle.detail.percentageText') }
            ];
        }
    },

    created() {
        this.repository = this.repositoryFactory.create('synlab_steered_customer_registration');
        this.getInvite();
    },

    methods: {
        getInvite() {
            this.repository
                .get(this.$route.params.id, Shopware.Context.api)
                .then((entity) => {
                    this.invite = entity;
                });
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.invite, Shopware.Context.api)
                .then(() => {
                    this.getInvite();
                    this.isLoading = false;
                    this.processSuccess = true;
                }).catch((exception) => {
                    this.isLoading = false;
                    this.createNotificationError({
                        title: this.$t('as-steered-customer-registration.errors.creation'),
                        message: exception
                    });
                });
        },

        saveFinish() {
            this.processSuccess = false;
        }
    }
});