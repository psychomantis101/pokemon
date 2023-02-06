<template>
    <h1>Pokemon</h1>
    <table class="table-primary">
        <thead>
            <tr>
                <th>Name</th>
                <th>Height</th>
                <th>Weight</th>
                <th>Image</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="poke in pokemon">
                <td>{{poke.name}}</td>
                <td>{{poke.height}}</td>
                <td>{{poke.weight}}</td>
                <td><img :src="poke.image" alt = 'a picture of a pokemon'></td>
                <td><button @click="editPokemon(poke.id)">Edit</button></td>
                <td><button @click="deletePokemon(poke.id)">Delete</button></td>
            </tr>
        </tbody>
    </table>

    <span>
        <button v-if="page !== 1" @click="changePage()">Back</button>
        <button v-if="page !== lastPage" @click="changePage(true)">Next</button>
    </span>

    <!-- edit model, could move to separate component-->
    <div class="modal-overlay" v-show="showEdit">
        <div class="modal">
            <h6>Edit</h6>
           <form>
               <div v-if="errors">
                   <ul v-for="error in errors">
                       {{error.toString()}}
                   </ul>
               </div>
               <div>
                   <label for="name">Name</label>
                   <input type="text" id="name"  v-model="selectedPokemon.name">
               </div>
               <div>
                   <label for="name">Weight</label>
                   <input type="number" id="weight" v-model="selectedPokemon.weight">
               </div>
               <div>
                   <label for="name">Height</label>
                   <input type="number" id="height" v-model="selectedPokemon.height">
               </div>
           </form>
            <button @click="updatePokemon()">Save</button>
        </div>
    </div>
    <!-- please wait model-->
    <div class="modal-overlay" v-show="loading">
        <div class="modal">
            <h2>
                Please wait, the first time the pokemon are gathered it takes a while.
            </h2>
        </div>
    </div>
</template>

<script>

export default {
    name: "app",
    data() {
        return {
            loading: false,
            page: 1,
            lastPage: 1,
            pokemon: [],
            selectedPokemon:[],
            showEdit:false,
            errors:null,
        };
    },

    created() {
        this.fetchPokemon();
    },

    methods: {
        async fetchPokemon(page=1) {
            this.loadingTimer = setTimeout(() => this.loading = true, 2000);

            await axios.get(`api/get-pokemon?page=${page}`)
                .then(response => {
                    this.pokemon = response.data.data;
                    this.lastPage = response.data.last_page;
                    clearTimeout(this.loadingTimer);
                    this.loading = false
                })

        },

        changePage(next = false) {
           next ? this.page++ : this.page--;
           this.fetchPokemon(this.page);
        },

        editPokemon(id) {
            this.selectedPokemon = this.pokemon.find(x => x.id === id);
            this.showEdit = true;
        },

        updatePokemon() {
            this.errors = null;

            axios.patch(`api/update-pokemon/` + this.selectedPokemon.id, {
                name: this.selectedPokemon.name,
                weight:this.selectedPokemon.weight,
                height:this.selectedPokemon.height
            }).then(response => {
                if (response.data === 'success') {
                    this.showEdit = false;
                }
            }).catch(e => {
                this.errors = e.response.data.errors;
            });
        },

        deletePokemon(id) {
            axios.delete(`api/delete-pokemon/` + id);

            const pokemon = this.pokemon.findIndex((obj) => obj.id === id);

            if (pokemon > -1) {
                this.pokemon.splice(pokemon, 1);
            }
        }
    },
}
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    background-color: #000000da;
}

.modal {
    text-align: center;
    background-color: white;
    height: 500px;
    width: 500px;
    margin-top: 10%;
    padding: 60px 0;
    border-radius: 20px;
}

h6 {
    font-weight: 500;
    font-size: 28px;
    margin: 20px 0;
}

p {
    font-size: 16px;
    margin: 20px 0;
}

button {
    cursor: pointer;
    background-color: #ac003e;
    width: 150px;
    height: 40px;
    color: white;
    font-size: 14px;
    border-radius: 16px;
    margin-top: 50px;
}
</style>
