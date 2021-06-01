<template>
  <div class="profile-settings">
    
    <div class="profile-settings-container">
      
      <div class="profile-settings__uploaders">

        <settings-banner-upload/>

        <avatar-upload/>

      </div>

      <div class="settings-form">

        
        <h3 class="settings-form__headline">Никнейм</h3>

        <div class="settings-form__field settings-form__nickname">
        
          <i class="settings-form__icon settings-form__icon-profile"></i>

          <span class="settings-form__nickname-span">pokimane</span>

          <img 
            class="settings-form__edit-icon pointer"
            src="/assets/images/i-settings-edit.svg" 
            alt=""
          >

        </div>

        <div class="settings-form__group settings-form__social">

          <h3 class="settings-form__headline">Ссылки на соцсети</h3>

          <div class="settings-form__field settings-form__vk">
          
            <i class="settings-form__icon settings-form__icon-vk"></i>

            <input 
              type="text"
              placeholder="vkontakte"
            >

          </div>

          <div class="settings-form__field settings-form__facebook">
          
            <i class="settings-form__icon settings-form__icon-fb"></i>

            <input 
              type="text"
              placeholder="facebook"
            >

          </div>

          <div class="settings-form__field settings-form__twitter">
          
            <i class="settings-form__icon settings-form__icon-tw"></i>

            <input 
              type="text"
              placeholder="twitter"
            >

          </div>

          <div class="settings-form__field settings-form__instagram">
          
            <i class="settings-form__icon settings-form__icon-inst"></i>

            <input 
              type="text"
              placeholder="instagram"
            >

          </div>

        </div>

        <div class="settings-form__group settings-form__email">

          <h3 class="settings-form__headline">Почта</h3>

          <div class="settings-form__field settings-form__mail">
        
            <i class="settings-form__icon settings-form__icon-mail"></i>

            <span class="settings-form__mail-span">pokimane.arts.work@gmail.com</span>

            <img 
              class="settings-form__edit-icon pointer"
              src="/assets/images/i-settings-edit.svg" 
              alt=""
            >

          </div>

        </div>

        <div class="settings-form__group settings-form__password">
        
          <h3 class="settings-form__headline">Пароль</h3>

          <div class="settings-form__field settings-form__mail">
        
            <i class="settings-form__icon settings-form__icon-password"></i>

            <button class="btn">Изменить пароль</button>

          </div>

        </div>

        <div class="settings-form__group settings-form__auth">

          <h3 class="settings-form__headline">Двухфакторная аутентификация</h3>

          <div class="settings-form__field settings-form__mail">
        
            <i class="settings-form__icon settings-form__icon-auth"></i>

            <button class="btn">Включить</button>

          </div>

        </div>

      </div>
      

    </div>

  </div>
</template>

<script>
import SettingsBannerUpload from '../components/SettingsBannerUpload.vue'
import AvatarUpload from '../components/AvatarUpload.vue'

export default {
  components: { SettingsBannerUpload, AvatarUpload },
  data() {
    return {

    }
  },
}
</script>