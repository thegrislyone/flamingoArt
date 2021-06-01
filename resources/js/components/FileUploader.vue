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
          <svg class="upload__img" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
            <path d="M28.6668 20.6665C28.2248 20.6665 27.8009 20.8421 27.4883 21.1546C27.1758 21.4672 27.0002 21.8911 27.0002 22.3332V22.9665L24.5335 20.4998C23.6625 19.6357 22.4854 19.1509 21.2585 19.1509C20.0316 19.1509 18.8545 19.6357 17.9835 20.4998L16.8168 21.6665L12.6835 17.5332C11.8004 16.6925 10.6278 16.2236 9.4085 16.2236C8.18922 16.2236 7.01663 16.6925 6.1335 17.5332L3.66683 19.9998V10.6665C3.66683 10.2245 3.84242 9.80054 4.15499 9.48798C4.46755 9.17542 4.89147 8.99983 5.3335 8.99983H17.0002C17.4422 8.99983 17.8661 8.82423 18.1787 8.51167C18.4912 8.19911 18.6668 7.77519 18.6668 7.33316C18.6668 6.89113 18.4912 6.46721 18.1787 6.15465C17.8661 5.84209 17.4422 5.66649 17.0002 5.66649H5.3335C4.00741 5.66649 2.73564 6.19328 1.79796 7.13096C0.86028 8.06864 0.333496 9.34041 0.333496 10.6665V30.6665C0.333496 31.9926 0.86028 33.2644 1.79796 34.202C2.73564 35.1397 4.00741 35.6665 5.3335 35.6665H25.3335C26.6596 35.6665 27.9314 35.1397 28.869 34.202C29.8067 33.2644 30.3335 31.9926 30.3335 30.6665V22.3332C30.3335 21.8911 30.1579 21.4672 29.8453 21.1546C29.5328 20.8421 29.1089 20.6665 28.6668 20.6665ZM5.3335 32.3332C4.89147 32.3332 4.46755 32.1576 4.15499 31.845C3.84242 31.5324 3.66683 31.1085 3.66683 30.6665V24.7165L8.50016 19.8832C8.74502 19.6498 9.07027 19.5197 9.4085 19.5197C9.74672 19.5197 10.072 19.6498 10.3168 19.8832L15.6002 25.1665L22.7668 32.3332H5.3335ZM27.0002 30.6665C26.9978 30.9855 26.8926 31.2953 26.7002 31.5498L19.1835 23.9998L20.3502 22.8332C20.4697 22.7112 20.6123 22.6143 20.7697 22.5482C20.9271 22.482 21.0961 22.448 21.2668 22.448C21.4376 22.448 21.6066 22.482 21.764 22.5482C21.9214 22.6143 22.064 22.7112 22.1835 22.8332L27.0002 27.6832V30.6665ZM34.8502 6.14983L29.8502 1.14983C29.6917 0.998093 29.5047 0.879151 29.3002 0.799827C28.8944 0.633131 28.4393 0.633131 28.0335 0.799827C27.8289 0.879151 27.642 0.998093 27.4835 1.14983L22.4835 6.14983C22.3281 6.30522 22.2048 6.48971 22.1207 6.69275C22.0366 6.89578 21.9933 7.1134 21.9933 7.33316C21.9933 7.777 22.1697 8.20265 22.4835 8.51649C22.7973 8.83033 23.223 9.00665 23.6668 9.00665C24.1107 9.00665 24.5363 8.83033 24.8502 8.51649L27.0002 6.34983V15.6665C27.0002 16.1085 27.1758 16.5324 27.4883 16.845C27.8009 17.1576 28.2248 17.3332 28.6668 17.3332C29.1089 17.3332 29.5328 17.1576 29.8453 16.845C30.1579 16.5324 30.3335 16.1085 30.3335 15.6665V6.34983L32.4835 8.51649C32.6384 8.67271 32.8228 8.7967 33.0259 8.88131C33.229 8.96593 33.4468 9.00949 33.6668 9.00949C33.8869 9.00949 34.1047 8.96593 34.3078 8.88131C34.5109 8.7967 34.6952 8.67271 34.8502 8.51649C35.0064 8.36156 35.1304 8.17722 35.215 7.97412C35.2996 7.77102 35.3432 7.55318 35.3432 7.33316C35.3432 7.11314 35.2996 6.8953 35.215 6.6922C35.1304 6.4891 35.0064 6.30477 34.8502 6.14983Z"/>
          </svg>

          <!-- <img 
            class="upload__img"
            src="/assets/images/upload.svg"
          > -->
          <!-- <img 
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
          > -->
          <span class="upload__headline">Загрузить изображение</span>
          <span class="upload__subtext">(минимальный размер 1000x1000)</span>
        </label>
      </div>

      <div 
        v-else 
        key="loaded"
        class="upload__loaded-block"
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

        this.$root.showNotification({
          title: 'Неверный формат файла',
          type: 'error'
        })

        return

      }

      let reader = new FileReader()

      let vm = this

      reader.addEventListener("load", function() {

        let img = new Image()
        img.addEventListener('load', function() {
          if (this.width >= 1000 && this.height >= 1000) {
            vm.imgSrc = reader.result
            vm.$emit('fileUpload', file)
          } else {
            vm.$root.showNotification({
              title: 'Изображение не соответствует минимальным размерам',
              type: 'error'
            })
          }
          
        })

        img.src = reader.result

        // let data = new FormData()

        // data.append("file", file)
        
      })

      reader.readAsDataURL(file)



    }
  }
}
</script>