<template>
  <div class="accueil-wrapper">
    <header class="navbar">
      <div class="navbar-brand">Gestion Enseignants</div>
      <a href="#" @click.prevent="showLogoutModal = true" class="btn-logout">
        <i class="fa-solid fa-sign-out-alt"></i> Déconnexion
      </a>
    </header>

    <main class="container">
      <h1 class="welcome-title">Bienvenue !</h1>
      <p class="welcome-subtitle">Connexion réussie</p>

      <div class="cards-grid">
        <router-link to="/accueil/ajout" class="card card-ajout">
          <i class="fa-solid fa-user-plus"></i>
          <h3>Ajouter un Enseignant</h3>
          <p>
            Saisir un nouvel enseignant dans la base de données (matricule, nom, taux horaire,
            heures).
          </p>
        </router-link>

        <router-link to="/accueil/liste" class="card card-liste">
          <i class="fa-solid fa-list-check"></i>
          <h3>Liste & Mise à jour</h3>
          <p>
            Consulter la table des enseignants, calculer les prestations, modifier ou supprimer un
            enregistrement.
          </p>
        </router-link>

        <router-link to="/accueil/bilan" class="card card-bilan">
          <i class="fa-solid fa-chart-pie"></i>
          <h3>Bilan & Graphiques</h3>
          <p>
            Visualiser les statistiques globales (prestations totale, minimale, maximale) sous forme
            de camembert ou d'histogramme.
          </p>
        </router-link>
      </div>
    </main>

    <transition name="fade">
      <div v-if="showLogoutModal" class="modal-overlay" @click.self="cancelLogout">
        <div class="modal-content">
          <div class="modal-icon">
            <i class="fa-solid fa-circle-exclamation"></i>
          </div>
          <h2>Attention</h2>
          <p>Vous allez être déconnecté. Voulez-vous vraiment continuer ?</p>
          <div class="modal-actions">
            <button class="btn-modal btn-cancel" @click="cancelLogout">Annuler</button>
            <button class="btn-modal btn-confirm" @click="confirmLogout">Se déconnecter</button>
          </div>
        </div>
      </div>
    </transition>
  </div>

  <transition name="toast-fade">
    <div v-if="toast.show" class="toast-notification success">
      {{ toast.message }}
    </div>
  </transition>
</template>

<script>
export default {
  name: 'AccueilView',
  data() {
    return {
      showLogoutModal: false,
      isConfirmedLogout: false,
      // c'est pour la notif de connexion reussie ou de creation de compte reussie
      toast: {
        show: false,
        message: '',
      },
    }
  },
  mounted() {
    // pour afficher la petit notif en bas là quand la page est chargée (c'est trop mimi)
    const internalNotification = this.$route.query.notif

    if (internalNotification === 'welcome') {
      this.triggerSuccessToast('Connexion réussie !')
    } else if (internalNotification === 'registered') {
      this.triggerSuccessToast('Compte créé avec succès !')
    }
  },
  beforeRouteLeave(to, from, next) {
    if (to.path.startsWith('/accueil/')) {
      return next()
    }
    if (!this.isConfirmedLogout) {
      this.showLogoutModal = true
      this.nextRoute = next
    } else {
      next()
    }
  },
  methods: {
    cancelLogout() {
      this.showLogoutModal = false
      if (this.nextRoute) {
        this.nextRoute(false)
        this.nextRoute = null
      }
    },
    confirmLogout() {
      this.showLogoutModal = false
      this.isConfirmedLogout = true
      if (this.nextRoute) {
        this.nextRoute()
      } else {
        this.$router.push('/')
      }
    },
    // pour lancer la notif
    triggerSuccessToast(msg) {
      this.toast.message = msg
      this.toast.show = true

      // jsp, askip c'est pour que le lien ne soit pas en bordel mais j'en vois pas trop l'utilité perso
      this.$router.replace({ query: null })

      setTimeout(() => {
        this.toast.show = false
      }, 3500)
    },
  },
}
</script>

<style scoped>
/* je voulais mettre en ligne mais vsc en a décidé autrement */
.accueil-wrapper {
  width: 100vw;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  background-image: url(../assets/bg2.jpg) center/cover no-repeat;
}
.navbar {
  width: 100%;
  background-color: #2c3e50;
  color: white;
  padding: 12px 30px;
  box-sizing: border-box;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.navbar-brand {
  font-size: 1.3rem;
  font-weight: bold;
}
.btn-logout {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 15px;
  background-color: #e74c3c;
  color: white;
  border-radius: 4px;
  font-size: 0.9rem;
  font-weight: 500;
  transition: background 0.2s ease;
}
.btn-logout:hover {
  background-color: #c0392b;
}
.container {
  max-width: 1000px;
  width: 90%;
  text-align: center;
  margin: auto;
  padding: 40px 0;
}
.welcome-title {
  color: #2c3e50;
  margin-bottom: 10px;
  font-size: 2rem;
}
.welcome-subtitle {
  color: #7f8c8d;
  margin-bottom: 50px;
  font-size: 1.1rem;
}
.cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;
}
.card {
  background: white;
  border-radius: 12px;
  padding: 35px 25px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  text-decoration: none;
  color: #333;
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  cursor: pointer;
  border: 1px solid #e2e8f0;
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(52, 152, 219, 0.2);
  border-color: #3498db;
}
.card i {
  font-size: 3rem;
  margin-bottom: 20px;
}
.card-ajout i {
  color: #2ecc71;
}
.card-liste i {
  color: #3498db;
}
.card-bilan i {
  color: #9b59b6;
}
.card h3 {
  margin: 10px 0;
  font-size: 1.3rem;
  color: #2c3e50;
}
.card p {
  font-size: 0.95rem;
  color: #7f8c8d;
  line-height: 1.5;
  text-align: center;
  margin: 0;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(8px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.modal-content {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.72);
  padding: 30px;
  border-radius: 16px;
  width: 90%;
  max-width: 400px;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}
.modal-icon {
  font-size: 3rem;
  color: #f39c12;
  margin-bottom: 15px;
}
.modal-content h2 {
  color: #2c3e50;
  margin-bottom: 10px;
  font-size: 1.5rem;
}
.modal-content p {
  color: #7f8c8d;
  font-size: 1rem;
  margin-bottom: 25px;
  line-height: 1.4;
}
.modal-actions {
  display: flex;
  gap: 15px;
  justify-content: center;
}
.btn-modal {
  padding: 12px 24px;
  border-radius: 10px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  outline: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.btn-cancel {
  background: rgba(255, 255, 255, 0.25);
  border: 1px solid rgba(255, 255, 255, 0.4);
  color: #2c3e50;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}
.btn-cancel:hover {
  background: rgba(255, 255, 255, 0.45);
  border-color: rgba(255, 255, 255, 0.6);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}
.btn-cancel:active {
  transform: translateY(0);
}
.btn-confirm {
  background: rgba(231, 76, 60, 0.2);
  border: 1px solid rgba(231, 76, 60, 0.45);
  color: #c0392b;
  box-shadow: 0 4px 15px rgba(231, 76, 60, 0.1);
}
.btn-confirm:hover {
  background: rgba(231, 76, 60, 0.35);
  border-color: rgba(231, 76, 60, 0.6);
  color: #b03a2e;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(231, 76, 60, 0.2);
}
.btn-confirm:active {
  transform: translateY(0);
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* pour forcer la notif à être en bas et à être visible surtout XD */
.toast-notification {
  position: fixed;
  bottom: 24px;
  left: 24px;
  padding: 0.85rem 1.35rem;
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
