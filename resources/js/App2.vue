<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">Home</nav>
    <header><h1>My Friends</h1></header>
    <new-friend @add-contact="addContact"></new-friend>
    <ul>
        <friend-contact
            v-for="friend in friends" 
            :key="friend.id"
            :id="friend.id"
            :name="friend.name"
            :phone-number="friend.phone"
            :email-address="friend.email"
            :is-favorite="friend.isFavorite"
            @toggle-favorite="toggleFavoriteStatus"
            @delete="deleteContact"
        ></friend-contact>
        <!-- <friend-contact 
            name="Ben Okafor"
            phone-number="09993 998498"
            email-address="ben@localhost.com"
            v-bind:is-favorite="true"
        ></friend-contact> 
        <friend-contact 
            name="Ugoeze Okafor"
            phone-number="09993 998498"
            email-address="ben@localhost.com"
            is-favorite="0"
        ></friend-contact> -->
    </ul>
</template>

<script>
// Use export default to inform Javascript of the default export Javascript file
export default {
    data() {
        return {
            friends: [
                {
                    id: "ayua",
                    name: "Nna-ayua Okafor",
                    phone: "0921 998998",
                    email: 'ayua@localhost.com',
                    isFavorite: true
                },
                {
                    id: "manuel",
                    name: "Emmanuel Okafor",
                    phone: "0921 998798",
                    email: 'emma@localhost.com',
                    isFavorite: false
                },
                {
                    id: "ugoeze",
                    name: "Ugoeze Okafor",
                    phone: "0921 923298",
                    email: 'ugo@localhost.com',
                    isFavorite: true
                }
            ]
        };
    },
    methods: {
        toggleFavoriteStatus(friendId) {
          const identifiedFriend = this.friends.find(
            (friend) => friend.id === friendId);
          identifiedFriend.isFavorite = !identifiedFriend.isFavorite;
        },
        addContact(name, phone, email) {
          const newFriendContact = {
            id: new Date().toISOString(),
            name: name,
            phone: phone,
            email: email,
            isFavorite: false
          };
          this.friends.push(newFriendContact);
        },
        deleteContact(friendId) {
          // Use filter funtion to remove the contact object from the array of contacts
          // Non-equality deletes the value, so if the id are equal return false
          // Equqlity keeps the value
          this.friends = this.friends.filter((friend) => friend.id !== friendId);
        }
    }
}

</script>

<style>
/* @import url('https//fonts.googleapis.com/css2?family=Jost&display=swap'); */

* {
  box-sizing: border-box;
}

html {
  font-family: 'Jost', sans-serif;
}

body {
  margin: 0;
}

header {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.26);
  margin: 3rem auto;
  border-radius: 10px;
  padding: 1rem;
  background-color: #58004d;
  color: white;
  text-align: center;
  width: 90%;
  max-width: 40rem;
}

#app ul {
  margin: 0;
  padding: 0;
  list-style: none;
}

#app li,
#app form {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.26);
  margin: 1rem auto;
  border-radius: 10px;
  padding: 1rem;
  text-align: center;
  width: 90%;
  max-width: 40rem;
}

#app h2 {
  font-size: 2rem;
  border-bottom: 4px solid #ccc;
  color: #58004d;
  margin: 0 0 1rem 0;
}

#app button {
  font: inherit;
  cursor: pointer;
  border: 1px solid #ff0077;
  background-color: #ff0077;
  color: white;
  padding: 0.05rem 1rem;
  box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.26);
}

#app button:hover,
#app button:active {
  background-color: #ec3169;
  border-color: #ec3169;
  box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.26);
}

#app input {
  font: inherit;
  padding: 0.15rem;
}
#app label {
  font-weight: bold;
  margin-right: 1rem;
  width: 7rem;
  display: inline-block;
}
#app form div {
  margin: 1rem 0;
}
</style>