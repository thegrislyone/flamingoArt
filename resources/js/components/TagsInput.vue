<template>
  <div class="tags-input">
    <div 
      class="tags-input__tags" 
      @click="inputFocus"
    >
    
      <span 
        v-for="(tag, key) in tags"
        :key="key"
        class="tags-input__tag no-select pointer"
        :class="{
          'tags-input__tag_selected': tag == selectedTag
        }"
        @click="selectTag(tag)"
        v-click-outside="unselectTag"
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

    <div 
      v-if="popularTags.length"
      class="tags-input__popular"
    >
      <h4>Варианты тегов:</h4>
      <template v-if="!popularTags.length">
        <div class="preloader"></div>
      </template>
      <template v-else>
        <div 
          class="tags-input__popular-tags"
        >
          <span 
            v-for="(tag, key) in popularTags"
            :key="tag.id"
            class="tags-input__popular-tag"
          >
            <span 
              class="pointer no-select" 
              @click="addTag(tag.name, key)"
            >{{ '#' + tag.name }}</span>
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
      popularTagsOriginal: [],
      popularTags: [],
      // popularTagsChoosen: [],
      tag: '',
      tags: [],
      selectedTag: null
    }
  },
  created() {
    this.$http.get('/api/tags/get-popular-tags?amount=10')
      .then(response => {
        const data = response.data

        this.popularTagsOriginal = data
        this.popularTags = data
        
      })
  },
  methods: {
    selectTag(tag) {
      if (this.selectedTag == tag) {
        this.unselectTag()
        return
      }
      this.selectedTag = tag
    },
    unselectTag(tag) {
      this.selectedTag = null
    },
    addTag(tag, key) {

      const alreadyIs = this.tags.find((tag) => {
        if (tag == this.tag) {
          return true
        }
        return false
      })

      if (alreadyIs) {
        this.tag = ''
        return
      }

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

      let findedIndex = null

      const finded = this.popularTagsOriginal.find((tag, index) => {
        if (tag.name == this.tags[this.tags.length - 1]) {
          findedIndex = index
          return true
        }
        return false
      })

      if (finded) {

        let firstPart = this.popularTags.slice(0, findedIndex)
        let secondPart = this.popularTags.slice(findedIndex)

        firstPart.push(finded)

        this.popularTags = firstPart.concat(secondPart)

      }

      if (!this.selectedTag) {
        this.tags.pop()
      } else {

        let selectedTagIndex = this.tags.length - 1

        this.tags.find((tag, index) => {

          if (this.selectedTag == tag) {
            selectedTagIndex = index
            return true
          }

          return false

        })

        this.tags = this.tags.slice(0, selectedTagIndex).concat(this.tags.slice(selectedTagIndex + 1))

        this.selectedTag = null
      }

      this.$emit('add-tag', this.tags)

    },
    inputFocus() {
      document.querySelector('.tags-input__input').focus()
    }
  }
}
</script>