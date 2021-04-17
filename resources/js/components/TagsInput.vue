<template>
  <div class="tags-input">
    <div class="tags-input__tags" @click="inputFocus">
      <span 
        v-for="(tag, key) in tags"
        :key="key"
        class="tags-input__tag"
      >
        {{ '#' + tag }}
      </span>

      <input 
        type="text"
        class="tags-input__input"
        @keydown.enter="addTag('')"
        v-model="tag"
      >

    </div>

    <div class="tags-input__popular">
      <h4>Варианты тегов:</h4>
      <template v-if="!popularTags.length">
        <div class="preloader"></div>
      </template>
      <template v-else>
        <div 
          v-if="popularTags.length"
          class="tags-input__popular-tags"
        >
          <span 
            v-for="(tag, key) in popularTags"
            :key="tag.id"
            class="tags-input__popular-tag"
          >
            <span class="pointer" @click="addTag(tag.name, key)">{{ '#' + tag.name }}</span>
          </span>
        </div>
      </template>
    </div>

  </div>
</template>

<script>
export default {
  props: {
    placeholder: {
      type: String,
      default: "Введите тег"
    }
  },
  data() {
    return {
      popularTags: [],
      tag: '',
      tags: []
    }
  },
  created() {
    this.$http.get('/api/tags?amount=10')
      .then(response => {
        const data = response.data

        this.popularTags = data
      })
  },
  methods: {
    addTag(tag, key) {

      if (tag) {
        this.tags.push(tag)
        this.popularTags = this.popularTags.slice(0, key).concat(this.popularTags.slice(key + 1))
      } else {
        this.tags.push(this.tag)
        this.tag = ''
      }
      
      this.$emit('add-tag', this.tags)
    },
    inputFocus() {
      document.querySelector('.tags-input__input').focus()
    }
  }
}
</script>