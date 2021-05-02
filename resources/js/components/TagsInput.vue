<template>
  <div class="tags-input">
    <div class="tags-input__tags" @click="inputFocus">
      <span 
        v-for="(tag, key) in tags"
        :key="key"
        class="tags-input__tag no-select"
      >
        {{ '#' + tag }}
      </span>

      <input 
        type="text"
        class="tags-input__input"
        @keydown.enter="addTag('')"
        @keydown.delete="deleteTag()"
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
            <span class="pointer no-select" @click="addTag(tag.name, key)">{{ '#' + tag.name }}</span>
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
      // popularTagsChoosen: [],
      tag: '',
      tags: []
    }
  },
  created() {
    this.$http.get('/api/tags/get-tags?amount=10')
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
        // this.popularTagsChoosen.push(tag)
      } else {
        this.tags.push(this.tag)
        this.tag = ''
      }
      
      this.$emit('add-tag', this.tags)
    },
    deleteTag() {
      
      if (event.key == 'Backspace' && this.tag) {
        return
      }

      // if (this.popularTagsChoosen.find((tag) => {
      //   if (tag.name == this.tags[this.tags.length - 1]) {
      //     return true
      //   }
      // })) {
      //   this.popularTags.push(this.tags[this.tags.length - 1])
      // }

      this.tags.pop()
      this.$emit('add-tag', this.tags)
    },
    inputFocus() {
      document.querySelector('.tags-input__input').focus()
    }
  }
}
</script>