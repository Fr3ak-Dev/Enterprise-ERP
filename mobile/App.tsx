import React from 'react';
import { AuthProvider } from './src/contexts/AuthContext';
import AppNavigator from './src/navigation/AppNavigator';

/**
 * AuthProvider: Provee contexto de autenticación global
 * AppNavigator: Maneja la navegación según estado de auth
 */

export default function App() {
  return (
    <AuthProvider>
      <AppNavigator />
    </AuthProvider>
  );
}