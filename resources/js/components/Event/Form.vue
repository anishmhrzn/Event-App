<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                {{ this.$route.params.id ? 'Edit' : 'Create' }} Event
            </div>
            <form @submit.prevent="submit" @keydown="form.onKeydown($event)">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="title" class="form-label">Title</label>
                            <input id="title" v-model="form.title" type="text" title="title"
                                   class="form-control" placeholder="Enter title"
                                   :class="[{'is-invalid': errors['title']}]">
                            <HasError :form="form" field="title"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Event End Date</label>
                            <date-range-picker class="form-control" :minDate="this.$route.params.id ? '' : today"
                                               ref="picker" opens="center" :locale-data="locale"
                                               v-model="form.dateRange" :ranges="false">
                                <div slot="input" slot-scope="picker">
                                    {{ moment(form.dateRange.startDate).format('YYYY-MM-DD') }} -
                                    {{ moment(form.dateRange.endDate).format('YYYY-MM-DD') }}
                                </div>
                            </date-range-picker>
                            <HasError :form="form" field="dateRange.startDate"/>
                            <HasError :form="form" field="dateRange.endDate"/>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea v-model="form.description" id="description" class="form-control"
                                      placeholder="Enter Event Description"
                                      :class="[{'is-invalid': errors['description']}]"></textarea>
                            <HasError :form="form" field="description"/>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <Button :form="form" class="btn btn-primary">Submit</Button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Form from "vform";
import DateRangePicker from 'vue2-daterange-picker'
import moment from 'moment';
import {HasError, Button} from 'vform/src/components/bootstrap4';

export default {
    components: {
        HasError,
        Button,
        DateRangePicker,
    },
    mounted() {
        if (this.$route.params.id) {
            this.getDetails(this.$route.params.id);
        }
    },
    created: function () {
        this.moment = moment;
    },
    data() {
        return {
            today : moment().format('YYYY-MM-DD'),
            form: new Form({
                title: '',
                description: '',
                dateRange: {
                    startDate: this.today,
                    endDate: this.today,
                },
            }),
            locale: {
                separator: ' to ',
                firstDay: 1
            },
            errors: {},
        }
    },
    methods: {
        getDetails(eventId) {
            axios.get(`/api/events/${eventId}`).then((response) => {
                console.log(response.data.data.start_date);
                this.form.title = response.data.data.title;
                this.form.description = response.data.data.description;
                this.form.dateRange.startDate = response.data.data.start_date;
                this.form.dateRange.endDate = response.data.data.end_date;
            });
        },
        submit() {
            if (this.$route.params.id) {
                this.form.put(`/api/events/${ this.$route.params.id }`).then((response) => {
                    this.$router.push({
                        name: 'home'
                    }, () => {
                        Toast.fire({
                            icon: 'success',
                            title: "Event edited successfully"
                        });
                    });
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            } else {
                console.log("helloooo");
                this.form.post("/api/events").then((response) => {
                    this.$router.push({
                        name: 'home'
                    }, () => {
                        Toast.fire({
                            icon: 'success',
                            title: "Event added Successfully"
                        });
                    });
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            }
        },
    }
}
</script>
