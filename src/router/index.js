import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import AccueilView from '../views/AccueilView.vue'
import AjoutView from '../views/AjoutView.vue'
import ListeView from '../views/ListeView.vue'
import BilanView from '../views/BilanView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/accueil',
      name: 'accueil',
      component: AccueilView
    },
    {
      path: '/accueil/ajout',
      name: 'ajout',
      component: AjoutView
    },
    {
      path: '/accueil/liste',
      name: 'liste',
      component: ListeView
    },
    {
      path: '/accueil/bilan',
      name: 'bilan',
      component: BilanView
    }
  ]
})

export default router