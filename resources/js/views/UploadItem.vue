<template>
  <div class="upload-item">
    <div class="upload-item__block">
      <div class="upload-item__upload">
        <file-uploader/>
      </div>
      <div class="upload-item__form">

        <form-group
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
        />

        <div class="price-input">
          <label class="form-group__label">Цена</label>
          <input 
            type="text" 
            placeholder="Введите цену"
            class="upload-item__field upload-item__price"
          >
          <div class="price-input__auction">
            <span>Аукцион</span>
            <switcher
              :value="auction"
              v-model="auction"
            />
            <img 
              class="price-input__tip pointer"
              src="/assets/images/i-tip.svg"
            >
          </div>
          
        </div>

        <button class="upload-item__button btn">
          Опубликовать
        </button>

      </div>
    </div>
  </div>
</template>

<script>
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
      name: {
        name: 'name',
        label: 'Название',
        requireServerValidation: false,
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
        class: 'upload-item__field',
        type: 'textarea',
        placeholder: 'Введите описание для своей работы (необязательно)',
        minLength: 2,
        maxLength: 32,
      },
    }
  },
}
</script>