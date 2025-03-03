<template>
  <section>
    <base-card>
      <h2>Submitted Experiences</h2>
      <div>
        <base-button @click="loadExperiences">Load Submitted Experiences</base-button>
      </div>
      <p v-if="isLoading">Loading...</p>
      <p v-else-if="!isLoading && error">
        {{ error }}
      </p>
      <p v-else-if="!isLoading && (!results || results.length === 0)">No stored experiences found. Strat adding some survey results first.</p>
      <!-- check for data from request is available -->
      <!-- <ul v-else-if="!isLoading && results && results.length > 0"> -->
      <ul v-else>
        <survey-result
          v-for="result in results"
          :key="result.id"
          :name="result.name"
          :rating="result.rating"
        ></survey-result>
      </ul>
    </base-card>
  </section>
</template>

<script>
import SurveyResult from './SurveyResult.vue';

export default {
  // props: ['results'],
  components: {
    SurveyResult,
  },
  data() {
    return {
      results: [],
      isLoading: false,
      error: null,
    };
  },
  methods: {
    loadExperiences() {
      this.isLoading = true;
      // Reset error to true when reloading begins again
      this.error = null;
      // Fetch returns a promise to listen to on which data can be get from
      // method: 'GET', // By default this its a get request
      fetch('https://vue-http-demo2-4ae0a-default-rtdb.europe-west1.firebasedatabase.app/surveys.json')
      .then((response) => { // Using arrow functions allow yout to use 'this' object
          // response.ok helps to check if the request was successful or not
          if (response.ok) {
            // use json() to parse the data thats part of the response
            // It also yeilds a promise
            return response.json();
          }
        }
      ).then((data) => {
          this.isLoading = false;
          // console.log(data);
          const results = [];
          for (const id in data) {
            results.push({ 
              id: id, 
              name: data[id].name, 
              rating: data[id].rating,
            })
          }
          this.results = results;
        }
      ).catch((error) => {
        // reset loading to false if there was an error
        this.isLoading = false;
        this.error = 'Failed to fetch data - please try again later.'
      });
    },
  },
  mounted() {
    this.loadExperiences();
  }
};
</script>

<style scoped>
ul {
  list-style: none;
  margin: 0;
  padding: 0;
}
</style>