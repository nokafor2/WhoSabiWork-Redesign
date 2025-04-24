<template>
    <div class="row mb-3">
        <div class="btn-group my-2" role="group" aria-label="Basic checkbox toggle button group">
            <TimeCheckBox ref="checkbox800am" count="1" :time="eightAM" :period="am" name="eight_AM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox830am" count="2" :time="eightThirtyAM" :period="am" name="eight_Thirty_AM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox900am" count="3" :time="nineAM" :period="am" name="nine_AM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox930am" count="4" :time="nineThirtyAM" :period="am" name="nine_Thirty_AM" @time-selected="updateSelectedTime"></TimeCheckBox>
        </div>

        <div class="btn-group my-2" role="group" aria-label="Basic checkbox toggle button group">
            <TimeCheckBox ref="checkbox1000am" count="5" :time="tenAM" :period="am" name="ten_AM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox1030am" count="6" :time="tenThirtyAM" :period="am" name="ten_Thirty_AM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox1100am" count="7" :time="elevenAM" :period="am" name="eleven_AM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox1130am" count="8" :time="elevenThirtyAM" :period="am" name="eleven_Thirty_AM" @time-selected="updateSelectedTime"></TimeCheckBox>
        </div>

        <div class="btn-group my-2" role="group" aria-label="Basic checkbox toggle button group">
            <TimeCheckBox ref="checkbox1200pm" count="9" :time="twelvePM" :period="pm" namme="twelve_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox1230pm" count="10" :time="twelveThirtyPM" :period="pm" name="twelve_Thirty_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox100pm" count="11" :time="onePM" :period="pm" name="one_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox130pm" count="12" :time="oneThirtyPM" :period="pm" name="one_Thirty_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
        </div>

        <div class="btn-group my-2" role="group" aria-label="Basic checkbox toggle button group">
            <TimeCheckBox ref="checkbox200pm" count="13" :time="twoPM" :period="pm" name="two_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox230pm" count="14" :time="twoThirtyPM" :period="pm" name="two_Thirty_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox300pm" count="15" :time="threePM" :period="pm" name="three_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox330pm" count="16" :time="threeThirtyPM" :period="pm" name="three_Thirty_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
        </div>

        <div class="btn-group my-2" role="group" aria-label="Basic checkbox toggle button group">
            <TimeCheckBox ref="checkbox400pm" count="17" :time="fourPM" :period="pm" name="four_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox430pm" count="18" :time="fourThirtyPM" :period="pm" name="four_Thirty_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox500pm" count="19" :time="fivePM" :period="pm" name="five_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox530pm" count="20" :time="fiveThirtyPM" :period="pm" name="five_Thirty_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
        </div>

        <div class="btn-group my-2" role="group" aria-label="Basic checkbox toggle button group">
            <TimeCheckBox ref="checkbox600pm" count="21" :time="sixPM" :period="pm" name="six_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox630pm" count="22" :time="sixThirtyPM" :period="pm" name="six_Thirty_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox700pm" count="23" :time="sevenPM" :period="pm" name="seven_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
            <TimeCheckBox ref="checkbox730pm" count="24" :time="sevenThirtyPM" :period="pm" name="seven_Thirty_PM" @time-selected="updateSelectedTime"></TimeCheckBox>
        </div>
    </div>
</template>


<script>
    import TimeCheckBox from './TimeCheckBox.vue';

    export default {
        components: {TimeCheckBox},
        emits: ['selected-times'],
        data() {
            return {
                am: 'AM',
                pm: 'PM',
                eightAM: '8:00',
                eightThirtyAM: '8:30',
                nineAM: '9:00',
                nineThirtyAM: '9:30',
                tenAM: '10:00',
                tenThirtyAM: '10:30',
                elevenAM: '11:00',
                elevenThirtyAM: '11:30',
                twelvePM: '12:00',
                twelveThirtyPM: '12:30',
                onePM: '01:00',
                oneThirtyPM: '01:30',
                twoPM: '2:00',
                twoThirtyPM: '2:30',
                threePM: '3:00',
                threeThirtyPM: '3:30',
                fourPM: '4:00',
                fourThirtyPM: '4:30',
                fivePM: '5:00',
                fiveThirtyPM: '5:30',
                sixPM: '6:00',
                sixThirtyPM: '6:30',
                sevenPM: '7:00',
                sevenThirtyPM: '7:30',
                selectedTime: [],
            }
        },
        methods: {
            updateSelectedTime(timeSelected, timeName) {
                if (timeSelected) {
                    this.selectedTime.push(timeName);
                } else {
                    this.selectedTime = this.selectedTime.filter((time) => time !== timeName);
                }
                this.$emit('selected-times', this.selectedTime);
            }
        }
    }
</script>
