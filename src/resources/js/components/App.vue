<template lang="">
    <div>
        <div class="container">
            <div class="row">
                <div class="form-group">
                    <label for="exampleInputEmail1">Number to Word</label>
                    <input
                        type="number"
                        class="form-control m-1"
                        id="input"
                        placeholder="Enter"
                        v-model="numberToWordInput"
                    />
                    <textarea
                        class="border m-1"
                        name=""
                        id=""
                        cols="30"
                        rows="5"
                        v-model="numberToWordText"
                    ></textarea>
                    <button class="mr-2" @click="numberToWord">
                        Translate
                    </button>
                    <button @click="converToUSD">Convert to USD</button>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="exampleInputEmail1">Word to Number</label>
                    <input
                        type="text"
                        class="form-control m-1"
                        id="input"
                        placeholder="Enter"
                        v-model="wordToNumberInput"
                    />
                    <textarea
                        class="border m-1"
                        name=""
                        id=""
                        cols="30"
                        rows="10"
                        v-model="wordToNumberText"
                    ></textarea>
                    <button @click="wordToNumber">Translate</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            numberToWordInput: "",
            numberToWordText: "",
            wordToNumberInput: "",
            wordToNumberText: "",
        };
    },
    methods: {
        numberToWord() {
            let number = this.numberToWordInput;
            if (this.numberToWordInput === "") {
                return;
            }
            axios
                .get(`http://localhost:8000/translate-to-word`, {
                    params: {
                        number,
                    },
                })
                .then((response) => {
                    this.numberToWordText = response.data;
                });
        },
        wordToNumber() {
            let word = this.wordToNumberInput;
            if (this.wordToNumber === "") {
                return;
            }
            axios
                .get(`http://localhost:8000/translate-to-number`, {
                    params: {
                        word,
                    },
                })
                .then((response) => {
                    this.wordToNumberText = response.data;
                });
        },
        converToUSD() {
            let number = this.numberToWordInput;
            if (this.numberToWordInput === "") {
                return;
            }
            axios
                .get(`http://localhost:8000/convert`, {
                    params: {
                        number,
                    },
                })
                .then((response) => {
                    this.numberToWordText = response.data;
                });
        },
    },
};
</script>
<style lang="scss">
.form-control {
    background-color: #fff0;
    &:focus {
        box-shadow: none;
    }
    &-underlined {
        border-width: 0;
        border-bottom-width: 1px;
        border-radius: 0;
        padding-left: 0;
    }
}
</style>
