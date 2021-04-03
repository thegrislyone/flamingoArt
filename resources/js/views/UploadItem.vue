<template>
  <div class="upload-item">
    <div class="upload-item__block">
      <div class="upload-item__upload">
        <file-uploader
          @fileUpload="setImage"
        />
      </div>
      <div class="upload-item__form">

        <form-group
          v-for="(field, key) in fields"
          :key="key"
          :ref="field.name"
          :formData="field"
          :class="field.externalClass"
          @add-tag="addTag"
          v-model="formData[field.name]"
        />

        <!-- <form-group
          :ref="name.name"
          :formData="name"
        />

        <form-group
          :ref="tags.name"
          :formData="tags"
        />

        <form-group
          class="upload-item__description"
          :ref="description.name"
          :formData="description"
        /> -->

        <div class="price-input">
          <label class="form-group__label">Цена</label>
          <input 
            type="text" 
            placeholder="Введите цену"
            class="upload-item__field upload-item__price"
            :disabled="formData.price.auction"
            v-model="formData.price.value"
          >
          <div class="price-input__auction">
            <span>Аукцион</span>
            <switcher
              :value="formData.price.auction"
              v-model="formData.price.auction"
              @input="formData.price.value = ''"
            />
            <img 
              class="price-input__tip pointer"
              src="/assets/images/i-tip.svg"
            >
          </div>
          
        </div>

        <button 
          class="upload-item__button btn"
          @click="upload"
        >
          Опубликовать
        </button>

      </div>
    </div>
  </div>
</template>

<script>

import { required, email, sameAs, minLength, maxLength } from 'vuelidate/lib/validators'

import FileUploader from '../components/FileUploader.vue'
import FormGroup from '../components/FormGroup.vue'
import TagsInput from '../components/TagsInput.vue'
import Switcher from '../components/Switcher.vue'

export default {
  components: {
    FileUploader,
    FormGroup,
    TagsInput,
    Switcher
  },
  data() {
    return {
      auction: false,
      formData: {
        name: '',
        tags: [],
        description: '',
        price: {
          auction: false,
          value: ''
        }
      },
      fields: {
        name: {
          name: 'name',
          label: 'Название',
          requireServerValidation: false,
          externalClass: '',
          class: 'upload-item__field',
          type: 'text',
          placeholder: 'Введите название',
          minLength: 2,
          maxLength: 32,
        },
        tags: {
          name: 'tags',
          label: 'Теги',
          requireServerValidation: false,
          externalClass: '',
          class: 'upload-item__field',
          type: 'tags',
          placeholder: 'Введите тег',
          minLength: 2,
          maxLength: 32,
        },
        description: {
          name: 'description',
          label: 'Описание',
          requireServerValidation: false,
          externalClass: "upload-item__description",
          class: 'upload-item__field',
          type: 'textarea',
          placeholder: 'Введите описание для своей работы (необязательно)',
          minLength: 2,
          maxLength: 32,
        },
      },
      
    }
  },
  validations() {
    
  },
  methods: {
    setImage(image) {

      let formData = new FormData()
      formData.append('file', image)

      this.$set(this.formData, 'img', formData)
    },
    addTag(tags, key) {
      this.formData[key] = tags
    },
    upload() {
      console.log(this.formData)

      this.$http.post('/api/item-load', this.formData)
        .then(response => {
          const data = response.data

          console.log(data)
        })

    }
  }
}
</script>