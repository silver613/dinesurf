<template>
     

    <div  v-if="formatedEvents.length > 0">

            <div  class="flex justify-between pl-3">
                <div>
                    <h4>Events</h4>
                </div>

                <div>
                    <select v-model="event_time"  name="event_type" @change="getEventByTime()" class=" pb-1 form-control placeholder-red-400  bg-white w-full   text-black">
                            <option :value="null" selected>All Events</option>
                            <option value="this_week" >This week</option>
                            <option value="this_month">This month</option>
                            <option value="next_month">Next month</option>
                        </select>
                </div>

            </div>

            <div class="flex flex-wrap  justify-center mt-4">
                <event-card  v-for="event in data"  :key="event.id"    :event="event" :type="'diner'"/>
            </div>
  </div>

<div class="px-4 py-8 flex items-center w-full justify-center" v-else>
    

          <empty-space 
              :title="'There Are No Live Events'"
              :description="'You can\'t see any upcoming events because  we don\'t have any. We\'re  working on getting some live events scheduled for you. please check back later!'"  
              :image="'/images/empty_images/6.png'"
              :noButton="true"
          />
  
  </div>
</template>
<script>
import EventCard from '../../Pages/Vendor/Events/EventCard.vue'
import moment from 'moment';
export default {
    props:['events', 'vendor'],
    components:{
     EventCard
    },
    data(){
        return{
            formatedEvents: this.events?.filter((item) => item.event_type != 'past'),
            event_time: null,
            data: this.formatedEvents
        }
    },

    methods:{
          getEventByTime(){
            var currentDate = moment();
            var nextMonth = moment().add(1, 'M');
            

            if(this.event_time === 'this_week'){
             var filtered = this.formatedEvents.filter(item => moment(item.start_date).isSame(currentDate, 'week'));  
             this.data =  filtered;
            }else if(this.event_time === 'this_month') {
            var filtered = this.formatedEvents.filter(item => moment(item.start_date).isSame(currentDate, 'month'));  
             this.data =  filtered;

            }else if(this.event_time === 'next_month') {
             var filtered = this.formatedEvents.filter(item => moment(item.start_date).isSame(nextMonth, 'month'));  
             this.data =  filtered;
            
            }else if(this.event_time === null) {
              this.data = this.formatedEvents;
            }
            
        }
    },
    mounted(){
        this.getEventByTime();
    }
}
</script>