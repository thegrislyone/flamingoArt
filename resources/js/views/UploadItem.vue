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
            type="number" 
            placeholder="Введите цену"
            class="upload-item__field upload-item__price"
            :disabled="formData.noSell"
            v-model="formData.price"
          >

          <div class="price-input__auction">
            <span>Без продажи</span>
            <switcher
              :value="formData.noSell"
              v-model="formData.noSell"
              @input="formData.price = ''"
            />

            <img 
              class="price-input__tip pointer"
              src="/assets/images/i-tip.svg"
              v-tooltip.bottom="'Опубликовать работу без возможности покупки'"
            >

          </div>
          
        </div>

        <button 
          class="upload-item__button btn"
          @click="upload"
        >
          Опубликовать
        </button>

        <div v-if="sendLoading" class="preloader preloader_inline"></div>

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
      sendLoading: false,

      formData: {
        img: null,
        name: '',
        tags: [],
        description: '',
        price: '',
        noSell: false
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
          required: true,
        },
        tags: {
          name: 'tags',
          label: 'Теги',
          requireServerValidation: false,
          externalClass: '',
          class: 'upload-item__field',
          type: 'tags',
          placeholder: 'Введите тег',
          required: false,
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
          required: false,
        }
      },
      
    }
  },
  validations() {

    let fieldsModel = {}

    for (let [key, field] of Object.entries(this.fields)) {

      fieldsModel[field.name] = {}

      // if (field.type == 'tags') {
      //   const tagsLength = (value) => !!value.length
      //   fieldsModel[field.name].tagsLength = tagsLength
      // }

      if (field.required) {
        fieldsModel[field.name].required = required
      }

      if (field.minLength) {
        fieldsModel[field.name].minLength = minLength
      }

      if (field.maxLength) {
        fieldsModel[field.name].maxLength = maxLength
      }

    }

    fieldsModel['price'] = {}
    fieldsModel['price'].required = required

    fieldsModel['img'] = {}
    fieldsModel['img'].required = required

    return {
      formData: fieldsModel
    }

  },
  mounted() {
    if (!this.$store.getters.user.email_verified_at) {
      this.$root.showNotification({
        'type': 'error',
        'title': "Подтвердите почту в настройках профиля, чтобы загружать свои работы"
      })
      this.$router.push('/')
    }
  },
  methods: {
    setImage(image) {
      this.formData.img = image
      // this.$set(this.formData, 'img', image)
    },
    addTag(tags, key) {
      this.formData[key] = tags
    },
    upload() {

      const formModel = this.$v.formData

      if (formModel.img.$invalid || formModel.name.$invalid || (formModel.price.$invalid && !this.formData.noSell)) {
        this.$v.$touch()
        this.$root.showNotification({
          title: 'Заполните данные', 
          type: 'error'
        })
        return
      }

      let formData = new FormData()

      for (const [key, value] of Object.entries(this.formData)) {
        formData.append(key, value)
      }

      this.sendLoading = true

      this.$http.post('/api/items/item-load', formData)
        .then(response => {
          const data = response.data

          if (data.notification) {
            this.$root.showNotification(data.notification)
          }

          this.sendLoading = false

          this.$router.push('/profile')

        })

    }
  }
}
</script>