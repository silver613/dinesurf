<template>
  <div @keyup="process" @click="process">
    <input
      class="
        border-gray-300
        focus:border-indigo-300
        focus:ring
        focus:ring-indigo-200
        focus:ring-opacity-50
        rounded-md
        shadow-sm
      "
      :value="modelValue"
      @input="process"
      :id="modelName"
      :autocomplete="modelName"
      type="tel"
      :name="modelName"
      :required="required"
    />
  </div>
</template>

<script>
import intlTelInput from "intl-tel-input";
import "intl-tel-input/build/css/intlTelInput.css";

export default {
  props: ["modelValue", "modelName", "required"],

  pops: {
    modelValue: "",
    modelName: "",
    required: {
      type:Boolean,
      default: false,
    },
  },
  emits: ["update:modelValue"],
  data() {
    return {
      phoneInput: null,
    };
  },

  methods: {
    focus() {
      this.$refs.input.focus();
    },

    update(modelValue){
      if (modelValue != undefined && modelValue != null) {
        var phone = modelValue;
        var dialcode = "+" + this.phoneInput.getSelectedCountryData().dialCode;

        if (phone?.includes(dialcode)) {
          var number = phone;
        } else {
          var phone = modelValue.slice(-10);
          var number = dialcode + phone;
        }

        this.$emit("update:modelValue", number);
        this.$emit("setValue", {
          val: number,
          model: this.modelName,
        });
      }
    },
    process(event) {
      if (event.target.value != undefined && event.target.value != null) {
        var phone = event.target.value;
        var dialcode = "+" + this.phoneInput.getSelectedCountryData().dialCode;

        if (phone.includes(dialcode)) {
          var number = phone;
        } else {
          var phone = event.target.value.slice(-10);
          var number = dialcode + phone;
        }

        console.log("getSelectedCountryData: ", this.phoneInput.getSelectedCountryData());

        this.$emit("update:modelValue", number);

        this.$emit("setValue", {
          val: number,
          model: this.modelName,
        });
      }
    },
    getIp(callback) {
      fetch("https://ipinfo.io/json?token=3423a3e5324846", {
        headers: {
          Accept: "application/json",
        },
      })
        .then((resp) => resp.json())
        .catch(() => {
          return {
            country: "us",
          };
        })
        .then((resp) => callback(resp.country));
    },
    initialize() {
      const input = document.querySelector("#" + this.modelName);
      this.phoneInput = intlTelInput(input, {
        initialCountry: "ng",
        geoIpLookup: this.getIp,
        preferredCountries: ["ng", "us"],
        utilsScript: "intl-tel-input/js/utils.js?1684676252775"
      });
    },
  },

  mounted() {
    this.initialize();
    this.update(this.modelValue);
  },
};
</script>

