<template>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-around">
                {{ title }}
                <select @change="filterEvents" v-model="selected">
                    <option v-for="filter in filters" :value="filter.value">{{ filter.text }}</option>
                </select>
                <router-link :to="{ name: 'eventCreate' }" class="nav-link">
                    <button type="button" class="btn btn-sm btn-primary">
                        <i class="fa fa-plus-square"></i>
                        Add New
                    </button>
                </router-link>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-condensed table-responsive" style="display:table">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Finished</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="event in events" v-bind:key="event.id">
                        <td>{{ event.title }}</td>
                        <td>{{ event.description }}</td>
                        <td>{{ event.start_date }}</td>
                        <td>{{ event.end_date }}</td>
                        <td>{{ event.is_finished ? 'Yes' : 'No' }}</td>
                        <td>
                            <router-link :to="{
                                name: 'eventEdit',
                                params: {
                                    id:event.id,
                                }
                            }" class="btn btn-sm btn-warning">
                                Edit
                            </router-link>
                            <button class="btn btn-sm btn-danger" @click="deleteEvent(event.id)">
                                Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            title: 'All Events',
            filters: [
                {value: "all", text: "All Events"},
                {value: "finished", text: "Finished Events"},
                {value: "upcoming", text: "Upcoming Events"},
                {value: "upcoming-7", text: "Upcoming events within 7 days"},
                {value: "finished-7", text: "Finished events of the last 7 days"},
            ],
            selected: 'all',
            events: [],
            allEvents: []
        };
    },

    mounted() {
        this.getEvents();
    },

    methods: {
        getEvents() {
            axios.get("/api/events").then((response) => {
                this.allEvents = this.events = response.data.data;
            });
        },
        filterEvents() {
            let values = this.filters.map(function (o) {
                return o.value
            });
            let index = values.indexOf(this.selected);
            this.title = this.filters[index].text;

            if (this.selected === 'all') {
                this.events = this.allEvents;
            } else {
                axios.post(`/api/events/filter`, {key: this.selected}).then((response) => {
                    this.events = response.data.data;
                });
            }
        },
        deleteEvent(id) {
            Swal.fire({
                title: 'Are you sure you want to delete this event ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                console.log(result);
                if (result.value) {
                    axios.delete(`/api/events/${id}`).then((response) => {
                        let i = this.events.map(item => item.id).indexOf(id);
                        this.events.splice(i, 1);
                        Toast.fire({
                            icon: 'success',
                            title: "Event deleted Successfully"
                        });
                    })
                }
            })
        }
    }
}
</script>
