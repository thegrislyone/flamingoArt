<template>
  <div 
    class="upload"
    :class="{
      'upload_loaded': !$isEmpty(imgSrc)
    }"
  >
    <transition name="fade" mode="out-in">
      <div 
        v-if="$isEmpty(imgSrc)" 
        key="unloaded"
      >
        <input 
          type="file"
          id="file-loader"
          @change.prevent="fileUpload(false)"
        >
        <label 
          for="file-loader"
          class="upload__block"
        >
          <img 
            v-if="windowWidth < 1024"
            class="upload__img"
            src="/assets/images/upload_35.png"
          >
          <img 
            v-else-if="windowWidth > 1024 && windowWidth < 1920"
            class="upload__img"
            src="/assets/images/upload_70.png"
          >
          <img 
            v-else
            class="upload__img"
            src="/assets/images/upload_70_gray.png"
          >
          <span class="upload__headline">Загрузить изображение</span>
          <span class="upload__subtext">(минимальный размер 1000x1000)</span>
        </label>
      </div>

      <div 
        v-else 
        key="loaded"
      >
        <img 
          :src="imgSrc"
          class="upload__loaded-img"
        >
      </div>
    </transition>

    
  </div>
</template>

<script>
export default {
  data() {
    return {
      loaderContainer: null,
      imgSrc: '',
      events: ['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'],
    }
  },
  computed: {
    windowWidth() {
      return this.$store.getters.windowWidth
    }
  },
  mounted() {
    this.loaderContainer = document.querySelector('.upload__block')

    this.events.forEach(function(evt) {
      this.loaderContainer.addEventListener(evt, this.addDefaultEvent)
    }.bind(this));

    document.addEventListener('dragenter', this.dragEnter)

    document.addEventListener('dragleave', this.dragLeave)

    this.loaderContainer.addEventListener('drop', this.drop)

  },
  methods: {
    drop() {
      this.fileUpload(event.dataTransfer.files[0])
    },
    dragEnter() {
      console.log('dragEnter')
    },
    dragLeave() {
      console.log('dragLeave')
    },
    addDefaultEvent() {
      event.preventDefault();
      event.stopPropagation();
    },
    fileUpload(outsideFile) {
      
      const file = (outsideFile) ? outsideFile : event.target.files[0]

      if (!file.type.includes('image')) {
        alert('это не изображение')
        return
      }

      let reader = new FileReader()

      let vm = this

      reader.addEventListener("load", function() {

        let img = new Image()
        img.addEventListener('load', function() {
          // if (this.width >= 1000 && this.height >= 1000) {
            vm.imgSrc = reader.result
          // } else {
          //   alert('Изображение не соответствует минимальным размерам')
          // }
          
        })
        img.src = reader.result
        

      })

      reader.readAsDataURL(file)

    }
  }
}
</script>