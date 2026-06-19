import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api' 

export const useAuthStore = defineStore('auth', () => {

    const token = ref(localStorage.getItem('token') || null)
    const user = ref(JSON.parse(localStorage.getItem('user')) || null)
    const role = ref(localStorage.getItem('role') || null)
    const loading = ref(false)
    const error = ref(null)

    const isAuthenticated = computed(() => !!token.value)
    
    // Khkhossossan had l-getters m9awdin bach t-man3/t-khlli un composant y-ban f l-Front-end
    const isHseAdmin = computed(() => role.value === 'HSE_ADMIN')
    const isOperator = computed(() => role.value === 'OPERATOR')
    const isAuditor = computed(() => role.value === 'AUDITOR')

    // ==========================================
    // 3. ACTIONS (Les Méthodes / API Calls)
    // ==========================================
    
    // Fonction d Login li t-7tajiha f la page Login.vue
    async function login(credentials) {
        loading.value = true
        error.value = null
        try {
            // Requête l-Laravel Backend via Axios
            const response = await api.post('/login', credentials)
            
            // Extraction dyal data (b7al l-format dyal Laravel Sanctum wla JWT)
            const responseData = response.data
            
            token.value = responseData.token
            user.value = responseData.user
            role.value = responseData.user.role // Strict casting m3a l-Enum backend

            // Stockage f l-navigateur bach t-sauvegarder la session
            localStorage.setItem('token', responseData.token)
            localStorage.setItem('user', JSON.stringify(responseData.user))
            localStorage.setItem('role', responseData.user.role)
            
            return true
        } catch (err) {
            error.value = err.response?.data?.message || 'Authentication failed. Please check your credentials.'
            return false
        } finally {
            loading.value = false
        }
    }

    // Fonction d Logout
    async function logout() {
        try {
            // Nettoyage f l-Backend hwa l-auwal (Optional but professional)
            await api.post('/auth/logout')
        } catch (err) {
            console.error('Logout error on backend:', err)
        } finally {
            // Nettoyage complet f l-Front-end
            token.value = null
            user.value = null
            role.value = null
            
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            localStorage.removeItem('role')
        }
    }

    // Renvoi dyal koulchi bach n-9dro n-stakhdmouhom f les composants
    return {
        token,
        user,
        role,
        loading,
        error,
        isAuthenticated,
        isHseAdmin,
        isOperator,
        isAuditor,
        login,
        logout
    }
})