<template>
  <div class="profile-settings">

    <settings-form-modal
      ref="formModal"
    />

    <confirmation
      v-if="showConfirm"
      :headline="activeConfirm.headline"
      :text="activeConfirm.text"
      :resetButtonText="activeConfirm.resetButtonText"
      :confirmButtonText="activeConfirm.confirmButtonText"
      @reset="confirmReset"
      @apply="confirmApply"
    />
    
    <div class="profile-settings-container">
      
      <div class="profile-settings__uploaders">

        <settings-banner-upload
          ref="banner-upload"
          :banner="user.banner"
          @fileUpload="bannerUpload"
        />

        <avatar-upload
          ref="avatar-upload"
          :avatar="user.avatar"
          @fileUpload="avatarUpload"
        />

      </div>

      <form 
        class="settings-form"
        @submit.prevent="formSubmit"
      >
        
        <h3 class="settings-form__headline">Никнейм</h3>

        <div class="settings-form__field settings-form__nickname">
        
          <i class="settings-form__icon settings-form__icon-profile"></i>

          <span class="settings-form__nickname-span">{{ user.login }}</span>

          <img 
            class="settings-form__edit-icon pointer"
            src="/assets/images/i-settings-edit.svg" 
            v-tooltip.bottom="'Изменить'"
            @click="showConfirmAction('nickname')"
          >

        </div>

        <div 
          class="settings-form__group settings-form__social"
        >

          <h3 class="settings-form__headline">Ссылки на соцсети</h3>

          <div 
            class="settings-form__field settings-form__vk"
            :class="{
              'settings-form__field_error': $v.social.vkLink.$error,
              'settings-form__field_success': !$v.social.vkLink.$error && $v.social.vkLink.$model
            }"
          >
          
            <i class="settings-form__icon settings-form__icon-vk"></i>

            <input 
              type="text"
              placeholder="vkontakte"
              @focus="showError = false"
              @blur="showError = true"
              v-model="$v.social.vkLink.$model"
              v-debounce:5000ms.lock="socialUpdate"
            >

          </div>

          <div 
            class="settings-form__field settings-form__facebook"
            :class="{
              'settings-form__field_error': $v.social.facebookLink.$error,
              'settings-form__field_success': !$v.social.facebookLink.$error && $v.social.facebookLink.$model
            }"
          >
          
            <i class="settings-form__icon settings-form__icon-fb"></i>

            <input 
              type="text"
              placeholder="facebook"
              @focus="showError = false"
              @blur="showError = true"
              v-model="$v.social.facebookLink.$model"
              v-debounce:5000ms.lock="socialUpdate"
            >

          </div>

          <div 
            class="settings-form__field settings-form__twitter"
            :class="{
              'settings-form__field_error': $v.social.twitterLink.$error,
              'settings-form__field_success': !$v.social.twitterLink.$error && $v.social.twitterLink.$model
            }"
          >
          
            <i class="settings-form__icon settings-form__icon-tw"></i>

            <input 
              type="text"
              placeholder="twitter"
              @focus="showError = false"
              @blur="showError = true"
              v-model="$v.social.twitterLink.$model"
              v-debounce:5000ms.lock="socialUpdate"
            >

          </div>

          <div 
            class="settings-form__field settings-form__instagram"
            :class="{
              'settings-form__field_error': $v.social.instagramLink.$error,
              'settings-form__field_success': !$v.social.instagramLink.$error && $v.social.instagramLink.$model
            }"
          >
          
            <i class="settings-form__icon settings-form__icon-inst"></i>

            <input 
              type="text"
              placeholder="instagram"
              @focus="showError = false"
              @blur="showError = true"
              v-model="$v.social.instagramLink.$model"
              v-debounce:5000ms.lock="socialUpdate"
            >

          </div>

          <div 
            class="settings-form__social-error"
            :class="{
              'settings-form__social-error_show': showError && $v.social.$invalid
            }"
          >
            Неверный формат ссылки
          </div>

        </div>

        <div class="settings-form__group settings-form__email">

          <h3 class="settings-form__headline">Почта</h3>

          <div class="settings-form__field settings-form__mail">
        
            <i class="settings-form__icon settings-form__icon-mail"></i>

            <span class="settings-form__mail-span">{{ user.email }}</span>

            <!-- <img 
              class="settings-form__edit-icon pointer"
              src="/assets/images/i-settings-edit.svg" 
              alt=""
              @click="showConfirmAction('email')"
            > -->

          </div>

          <div 
            v-if="!user.email_verified_at"
            class="settings-form__unconfirmed-email"
          >
            <span class="settings-form__unconfirmed-email-text">Почта не подтверждена</span>
            <button 
              class="settings-form__unconfirmed-email-button btn"
              type="button"
              @click="emailConfirm"
            >Подтвердить</button>
          </div>

        </div>

        <div class="settings-form__group settings-form__password">
        
          <h3 class="settings-form__headline">Пароль</h3>

          <div class="settings-form__field settings-form__mail">
        
            <i class="settings-form__icon settings-form__icon-password"></i>

            <button 
              type="button"
              class="btn"
              @click="showConfirmAction('password')"
            >Изменить пароль</button>

          </div>

        </div>

        <div class="settings-form__group settings-form__auth">

          <h3 class="settings-form__headline">Двухфакторная аутентификация</h3>

          <div class="settings-form__field settings-form__mail">
        
            <i class="settings-form__icon settings-form__icon-auth"></i>

            <button 
              type="button"
              class="btn"
            >Включить</button>

          </div>

        </div>

      </form>
      

    </div>

  </div>
</template>

<script>

import SettingsBannerUpload from '../components/SettingsBannerUpload.vue'
import AvatarUpload from '../components/AvatarUpload.vue'

import Confirmation from '../components/Confirmation.vue'
import SettingsFormModal from '../components/SettingsFormModal.vue'

import { required, email, sameAs, minLength, maxLength } from 'vuelidate/lib/validators'

export default {
  components: { SettingsBannerUpload, AvatarUpload, Confirmation, SettingsFormModal },
  data() {
    return {

      social: {
        vkLink: '',
        facebookLink: '',
        twitterLink: '',
        instagramLink: '' 
      },

      showConfirm: false,
      activeConfirm: null,
      activeConfirmType: '',

      confirms: {
        nickname: {
          allowen: {
            headline: 'Изменить никнейм?',
            text: 'В следущий раз вы сможете изменить его через месяц.',
            resetButtonText: 'Отмена',
            confirmButtonText: 'Изменить'
          },
          notAllowen: {
            headline: 'Действие невозможно',
            text: `Менять никейм можно раз в  месяц. В последний раз вы изменяли его ${this.$moment(this.$store.getters.user.login_changed_at).format('DD.MM.YYYY')}`,
            resetButtonText: 'Отмена',
            confirmButtonText: 'Ок'
          }
        },
        email: {
          allowen: {
            headline: 'Изменить почтовый ящик?',
            text: 'В следущий раз вы сможете изменить его через месяц.',
            resetButtonText: 'Отмена',
            confirmButtonText: 'Изменить'
          },
          notAllowen: {
            headline: 'Действие невозможно',
            text: `Менять пароль можно раз в  месяц. В последний раз вы изменяли его ${this.$moment(this.$store.getters.user.password_changed_at).format('DD.MM.YYYY')}`,
            resetButtonText: 'Отмена',
            confirmButtonText: 'Ок'
          }
        },
        password: {
          headline: 'Мы отправили вам письмо',
          text: `Перейдите по ссылке в письме для того чтобы изменить пароль.`,
          resetButtonText: 'Отмена',
          confirmButtonText: 'Ок'
        },
        auth: {
          allowen: {
            headline: '',
            text: '',
            resetButtonText: '',
            confirmButtonText: ''
          },
          notAllowen: {
            headline: '',
            text: '',
            resetButtonText: '',
            confirmButtonText: ''
          }
        }
      },
      
      showError: false
    }
  },
  validations() {

    let form = {}

    for (const index in this.social) {

      const isLink = (value) => value.indexOf('https://') >= 0 || !value


      form[index] = {}

      form[index]['linkValidator'] = isLink

    }

    return {
      social: form
    }

  },
  computed: {
    user() {
      return this.$store.getters.user
    }
  },
  created() {

    this.social['vkLink'] = this.user.vkontakte || ''
    this.social['twitterLink'] = this.user.twitter || ''
    this.social['facebookLink'] = this.user.facebook || ''
    this.social['instagramLink'] = this.user.instagram || ''
    
  },
  mounted() {
    this.passwordChangeCheck()
  },
  methods: {
    showConfirmAction(type) {

      this.activeConfirmType = type

      if (type == 'nickname') {

        if (this.getIsAllowen('nickname')) {
          this.activeConfirm = this.confirms.nickname.allowen
        } else {
          this.activeConfirm = this.confirms.nickname.notAllowen
        }

      } else if (type == 'email') {

        if (this.getIsAllowen('email')) {
          this.activeConfirm = this.confirms.email.allowen
        } else {
          this.activeConfirm = this.confirms.email.notAllowen
        }

      } else if (type == 'password') {
        this.activeConfirm = this.confirms.password
        this.passwordChange()
      }

      this.showConfirm = true

    },
    getIsAllowen(type) {

      const now = new Date()

      let updated;

      if (type == 'nickname') {
        updated = this.user.login_changed_at
      } else if (type == 'email') {
        updated = this.user.email_changed_at
      } else if (type == 'password') {
        
      }

      if (!updated) {
        return true
      }

      const updatedDate = new Date(updated)
          
      return !!Math.floor(Math.abs(now.getTime() - updatedDate.getTime()) / (1000 * 3600 * 24 * 30));

    },
    confirmReset() {

      this.$modal.hide('confirm')

      this.showConfirm = false
      this.activeConfirm = null
      
    },
    confirmApply() {

      this.$modal.hide('confirm')

      this.showConfirm = false
      this.activeConfirm = null
      
      if (this.activeConfirmType == 'nickname') {
        if (this.getIsAllowen('nickname')) {
          this.$refs['formModal'].showModal('nickname')
        }
      } else if (this.activeConfirmType == 'password') {
        // this.passwordChange()

        

      }

    },
    socialUpdate() {
      
      if (this.$v.social.$invalid) {
        return
      }

      this.$http.post('/api/auth/set-user-socials', this.social)

    },
    avatarUpload(file) {

      let formData = new FormData()

      formData.append('avatar', file)

      this.$http.post('/api/auth/set-user-avatar', formData)
        .then(response => {
          const data = response.data

          if (data.notification) {
            this.$root.showNotification(data.notification)
          }

          if (data.user) {
            this.$store.commit('setUser', data.user)
            this.$refs['avatar-upload'].avatarSrc = data.user.avatar
          }

        })

    },
    bannerUpload(file) {

      let formData = new FormData()

      formData.append('banner', file)

      this.$http.post('/api/auth/set-user-banner', formData)
        .then(response => {
          const data = response.data

          if (data.notification) {
            this.$root.showNotification(data.notification)
          }

          if (data.user) {
            this.$store.commit('setUser', data.user)
            this.$refs['banner-upload'].bannerSrc = data.user.banner
          }
        })

    },
    emailConfirm() {

      this.$http.get('/api/auth/email/email-confirm-request')
        .then(response => {

          const data = response.data

          if (data.notification) {
            this.$root.showNotification(data.notification)
          }

        })
      
    },
    passwordChange() {

      this.$http.get('/api/auth/password/password-change-request')

    },
    passwordChangeCheck() {

      if (this.$route.query['password-update-token'] != undefined) {
        this.$http.post('/api/auth/password/is-password-change-process', {
          token: this.$route.query['password-update-token']
        }).then(response => {

          const data = response.data

          if (data.status) {
            this.$refs['formModal'].showModal('password')
          } else {
            this.$router.push('/profile-settings')
          }

          
        })
        
      }

    }
  }
}
</script>