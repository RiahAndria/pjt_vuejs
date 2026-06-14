<template>
  <div class="login-container">
    <transition name="fade" mode="out-in">
      <h2 :key="isLoginMode">{{ isLoginMode ? 'Se connecter' : 'Créer un compte' }}</h2>
    </transition>

    <transition name="fade" mode="out-in">
      <p class="subtitle" :key="isLoginMode">
        {{ isLoginMode ? 'Veuillez vous connecter' : 'Rejoignez-nous' }}
      </p>
    </transition>

    <form @submit.prevent="handleSubmit">
      <transition name="slide-fade">
        <div class="input-group" v-if="!isLoginMode">
          <label for="username">Nom d'utilisateur</label>
          <input
            type="text"
            id="username"
            v-model="username"
            :class="usernameClass"
            placeholder="Votre pseudo"
          />
          <small v-if="usernameTouched && !isUsernameValid" class="hint-error"
            >Requis : 3 caractères minimum.</small
          >
        </div>
      </transition>

      <div class="input-group">
        <label for="email">Adresse Email</label>
        <input
          type="email"
          id="email"
          v-model="email"
          :class="emailClass"
          placeholder="exemple@mail.com"
        />
        <small v-if="emailTouched && !isEmailValid" class="hint-error"
          >Format d'email invalide.</small
        >
      </div>

      <div class="input-group">
        <label for="password">Mot de passe</label>
        <input
          type="password"
          id="password"
          v-model="password"
          :class="passwordClass"
          placeholder="**********"
        />
        <small v-if="passwordTouched && !isPasswordValid" class="hint-error"
          >Requis : 8 caractères min. et 1 caractère spécial.</small
        >
      </div>

      <transition name="slide-fade">
        <div class="input-group" v-if="!isLoginMode">
          <label for="confirmPassword">Confirmer le mot de passe</label>
          <input
            type="password"
            id="confirmPassword"
            v-model="confirmPassword"
            :class="confirmPasswordClass"
            placeholder="**********"
          />
          <small v-if="confirmPasswordTouched && !isConfirmPasswordValid" class="hint-error"
            >Les mots de passe ne correspondent pas.</small
          >
        </div>
      </transition>

      <button type="submit" class="btn-submit" :disabled="!isFormValid">
        <transition name="fade" mode="out-in">
          <span :key="isLoginMode">{{ isLoginMode ? 'Se connecter' : "S'inscrire" }}</span>
        </transition>
      </button>
    </form>

    <div class="login-footer">
      <transition name="fade" mode="out-in">
        <p v-if="isLoginMode" key="login">
          Pas encore de compte ? <a href="#" @click.prevent="toggleMode">S'inscrire</a>
        </p>
        <p v-else key="register">
          Déjà un compte ? <a href="#" @click.prevent="toggleMode">Se connecter</a>
        </p>
      </transition>
    </div>
  </div>

  <transition name="toast-fade">
    <div v-if="toast.show" class="toast-notification" :class="toast.type">
      {{ toast.message }}
    </div>
  </transition>
</template>

<script>
export default {
  name: 'LoginView',
  data() {
    return {
      isLoginMode: true,
      email: '',
      password: '',
      confirmPassword: '',
      username: '',
      emailTouched: false,
      passwordTouched: false,
      confirmPasswordTouched: false,
      usernameTouched: false,
      toast: {
        show: false,
        message: '',
        type: 'success',
      },
    }
  },
  watch: {
    email(val) {
      if (val.length > 0) this.emailTouched = true
    },
    password(val) {
      if (val.length > 0) this.passwordTouched = true
    },
    confirmPassword(val) {
      if (val.length > 0) this.confirmPasswordTouched = true
    },
    username(val) {
      if (val.length > 0) this.usernameTouched = true
    },
  },
  computed: {
    isUsernameValid() {
      return this.username.trim().length >= 3
    },
    isEmailValid() {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email.trim())
    },
    isPasswordValid() {
      return this.password.length >= 8 && /[^A-Za-z0-9]/.test(this.password)
    },
    isConfirmPasswordValid() {
      return this.password === this.confirmPassword
    },
    isFormValid() {
      return this.isLoginMode
        ? this.isEmailValid && this.isPasswordValid
        : this.isEmailValid &&
            this.isPasswordValid &&
            this.isConfirmPasswordValid &&
            this.isUsernameValid
    },
    usernameClass() {
      return !this.usernameTouched ? '' : this.isUsernameValid ? 'valid' : 'invalid'
    },
    emailClass() {
      return !this.emailTouched ? '' : this.isEmailValid ? 'valid' : 'invalid'
    },
    passwordClass() {
      return !this.passwordTouched ? '' : this.isPasswordValid ? 'valid' : 'invalid'
    },
    confirmPasswordClass() {
      return !this.confirmPasswordTouched ? '' : this.isConfirmPasswordValid ? 'valid' : 'invalid'
    },
  },
  methods: {
    toggleMode() {
      this.isLoginMode = !this.isLoginMode
      this.email = ''
      this.password = ''
      this.confirmPassword = ''
      this.username = ''
      this.emailTouched =
        this.passwordTouched =
        this.confirmPasswordTouched =
        this.usernameTouched =
          false
    },
    showToast(message, type = 'success') {
      this.toast.message = message
      this.toast.type = type
      this.toast.show = true
      setTimeout(() => {
        this.toast.show = false
      }, 3500)
    },
    handleSubmit() {
      this.emailTouched = true
      this.passwordTouched = true
      if (!this.isLoginMode) {
        this.usernameTouched = true
      }

      if (this.isFormValid) {
        const formData = new FormData()
        formData.append('isLoginMode', this.isLoginMode)
        formData.append('email', this.email)
        formData.append('password', this.password)
        if (!this.isLoginMode) {
          formData.append('username', this.username)
        }

        fetch('http://localhost/apis/validation.php', {
          method: 'POST',
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              if (this.isLoginMode) {
                // la petite notif est en bas à gauche (j'aime trop cette ajout XD)
                this.$router.push({ path: '/accueil', query: { notif: 'welcome' } })
              } else {
                this.$router.push({ path: '/accueil', query: { notif: 'registered' } })
              }
            } else {
              // Reste sur la page en cas d'erreur avec la notif en bas à gauche
              this.showToast(data.message, 'error')
            }
          })
          .catch((error) => {
            console.error("Erreur de communication avec l'API :", error)
            this.showToast('Impossible de joindre le serveur.', 'error')
          })
      }
    },
  },
}
</script>

<style scoped>
.login-container {
  background: rgba(255, 255, 255, 0.45);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.6);
  padding: 2.5rem;
  border-radius: 16px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.08);
}

h2 {
  font-size: 1.8rem;
  margin-bottom: 0.5rem;
  text-align: center;
}
.subtitle {
  color: var(--text-gray);
  font-size: 0.9rem;
  text-align: center;
  margin-bottom: 2rem;
}
.input-group {
  margin-bottom: 1.5rem;
}
.input-group label {
  display: block;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: var(--text-dark);
}
.input-group input {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.8);
  outline: none;
  font-size: 1rem;
  transition: all 0.2s;
}
.input-group input:focus {
  border-color: var(--primary-blue);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
.btn-submit {
  width: 100%;
  padding: 0.75rem;
  background: var(--primary-blue);
  color: var(--white);
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
  margin-top: 0.5rem;
}
.btn-submit:hover {
  background: var(--hover-blue);
}
.login-footer {
  margin-top: 1.5rem;
  text-align: center;
  font-size: 0.9rem;
  color: var(--text-gray);
}
.input-group input.invalid {
  border-color: var(--error-red) !important;
  background: rgba(239, 68, 68, 0.03);
}
.input-group input.valid {
  border-color: var(--primary-blue) !important;
  background: rgba(37, 99, 235, 0.03);
}
.hint-error {
  color: var(--error-red);
  font-size: 0.75rem;
  display: block;
  margin-top: 0.35rem;
}
.btn-submit:disabled {
  background: #cbd5e1;
  color: #94a3b8;
  cursor: not-allowed;
  box-shadow: none;
}

/* notif fixée en bas à gauche */
.toast-notification {
  position: fixed;
  bottom: 24px;
  left: 24px;
  padding: 0.85rem 1.25rem;
  border-radius: 10px;
  color: white;
  font-weight: 500;
  font-size: 0.9rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  z-index: 9999;
}
.toast-notification.success {
  background: rgba(34, 197, 94, 0.95);
  border: 1px solid rgba(255, 255, 255, 0.2);
}
.toast-notification.error {
  background: rgba(239, 68, 68, 0.95);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Animations */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
.slide-fade-enter-active {
  transition: all 0.35s ease-out;
  max-height: 120px;
  overflow: hidden;
}
.slide-fade-leave-active {
  transition: all 0.25s ease-in;
  max-height: 120px;
  overflow: hidden;
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(-15px);
  opacity: 0;
  max-height: 0;
  margin-bottom: 0;
  padding-bottom: 0;
}

.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.toast-fade-enter-from {
  transform: translateY(50px) scale(0.9);
  opacity: 0;
}
.toast-fade-leave-to {
  transform: translateX(-50px);
  opacity: 0;
}
</style>
