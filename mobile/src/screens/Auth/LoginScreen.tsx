import React, { useState } from 'react';
import {
    View,
    Text,
    StyleSheet,
    KeyboardAvoidingView,
    Platform,
    ScrollView,
    Alert
} from 'react-native';
import { useAuth } from '../../contexts/AuthContext';
import { Button, Input } from '../../components/common';

const LoginScreen = () => {
    const { login } = useAuth();

    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [errors, setErrors] = useState({ email: '', password: '' });
    const [isLoading, setIsLoading] = useState(false);

    /**
     * Validar formulario
     */
    const validate = (emailValue?: string, passwordValue?: string): boolean => {
        let isValid = true;
        const newErrors = { email: '', password: '' };

        const emailToValidate = emailValue !== undefined ? emailValue : email;
        const passwordToValidate = passwordValue !== undefined ? passwordValue : password;

        // Validar email
        if (!emailToValidate) {
            newErrors.email = 'El email es requerido';
            isValid = false;
        } else if (!/\S+@\S+\.\S+/.test(emailToValidate)) {
            newErrors.email = 'Email inválido';
            isValid = false;
        }

        // Validar password
        if (!passwordToValidate) {
            newErrors.password = 'La contraseña es requerida';
            isValid = false;
        } else if (passwordToValidate.length < 6) {
            newErrors.password = 'Mínimo 6 caracteres';
            isValid = false;
        }

        setErrors(newErrors);
        return isValid;
    };

    /**
     * Manejar submit
     */
    const handleLogin = async (emailValue?: string, passwordValue?: string) => {
        setErrors({ email: '', password: '' });

        if (!validate(emailValue, passwordValue)) {
            return;
        }

        const emailToUse = emailValue !== undefined ? emailValue : email;
        const passwordToUse = passwordValue !== undefined ? passwordValue : password;

        try {
            setIsLoading(true);
            await login(emailToUse, passwordToUse);   // Si el login es exitoso, AuthContext maneja la navegación automáticamente
        } catch (error: any) {
            Alert.alert(
                'Error',
                error.message || 'Error al iniciar sesión',
                [{ text: 'OK' }]
            );
        } finally {
            setIsLoading(false);
        }
    };

    /**
     * Login rápido para testing
     */
    const handleQuickLogin = async () => {
        const quickEmail = 'admin@enterpriseerp.com';
        const quickPassword = 'password';
        setEmail(quickEmail);
        setPassword(quickPassword);
        handleLogin(quickEmail, quickPassword);
    };

    return (
        <KeyboardAvoidingView
            style={styles.container}
            behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        >
            <ScrollView
                contentContainerStyle={styles.scrollContent}
                keyboardShouldPersistTaps="handled"
            >
                <View style={styles.content}>
                    {/* Header */}
                    <View style={styles.header}>
                        <Text style={styles.title}>EnterpriseERP</Text>
                        <Text style={styles.subtitle}>Inicia sesión para continuar</Text>
                    </View>

                    {/* Form */}
                    <View style={styles.form}>
                        <Input
                            label="Email"
                            value={email}
                            onChangeText={setEmail}
                            placeholder="tu@email.com"
                            error={errors.email}
                            keyboardType="email-address"
                            autoCapitalize="none"
                        />

                        <Input
                            label="Contraseña"
                            value={password}
                            onChangeText={setPassword}
                            placeholder="••••••••"
                            error={errors.password}
                            secureTextEntry
                        />

                        <Button
                            title="Iniciar Sesión"
                            onPress={handleLogin}
                            isLoading={isLoading}
                        />

                        {/* Quick Login para desarrollo */}
                        <View style={styles.divider}>
                            <View style={styles.dividerLine} />
                            <Text style={styles.dividerText}>o</Text>
                            <View style={styles.dividerLine} />
                        </View>

                        <Button
                            title="Login Rápido (Demo)"
                            onPress={handleQuickLogin}
                            variant="secondary"
                            disabled={isLoading}
                        />
                    </View>

                    {/* Footer */}
                    <Text style={styles.footer}>
                        Usuario demo: admin@enterpriseerp.com{'\n'}
                        Contraseña: password
                    </Text>
                </View>
            </ScrollView>
        </KeyboardAvoidingView>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f9fafb',
    },
    scrollContent: {
        flexGrow: 1,
        justifyContent: 'center',
    },
    content: {
        padding: 24,
    },
    header: {
        alignItems: 'center',
        marginBottom: 32,
    },
    title: {
        fontSize: 32,
        fontWeight: 'bold',
        color: '#1f2937',
        marginBottom: 8,
    },
    subtitle: {
        fontSize: 16,
        color: '#6b7280',
    },
    form: {
        backgroundColor: '#fff',
        padding: 24,
        borderRadius: 12,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 8,
        elevation: 3,
    },
    divider: {
        flexDirection: 'row',
        alignItems: 'center',
        marginVertical: 20,
    },
    dividerLine: {
        flex: 1,
        height: 1,
        backgroundColor: '#e5e7eb',
    },
    dividerText: {
        marginHorizontal: 12,
        color: '#9ca3af',
        fontSize: 14,
    },
    footer: {
        marginTop: 24,
        textAlign: 'center',
        fontSize: 12,
        color: '#6b7280',
        lineHeight: 18,
    },
});

export default LoginScreen;