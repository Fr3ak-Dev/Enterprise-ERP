import React from 'react';
import { TouchableOpacity, Text, StyleSheet, ActivityIndicator } from 'react-native';

interface ButtonProps {
    title: string;
    onPress: () => void;
    isLoading?: boolean;
    variant?: 'primary' | 'secondary';
    disabled?: boolean;
}

const Button = ({
    title,
    onPress,
    isLoading = false,
    variant = 'primary',
    disabled = false
}: ButtonProps) => {
    const isPrimary = variant === 'primary';

    return (
        <TouchableOpacity
            style={[
                styles.button,
                isPrimary ? styles.primaryButton : styles.secondaryButton,
                (disabled || isLoading) && styles.disabledButton,
            ]}
            onPress={onPress}
            disabled={disabled || isLoading}
            activeOpacity={0.8}
        >
            {isLoading ? (
                <ActivityIndicator color={isPrimary ? '#fff' : '#3b82f6'} />
            ) : (
                <Text style={[
                    styles.buttonText,
                    isPrimary ? styles.primaryButtonText : styles.secondaryButtonText,
                ]}>
                    {title}
                </Text>
            )}
        </TouchableOpacity>
    );
};

const styles = StyleSheet.create({
    button: {
        paddingVertical: 14,
        paddingHorizontal: 24,
        borderRadius: 8,
        alignItems: 'center',
        justifyContent: 'center',
        minHeight: 48,
    },
    primaryButton: {
        backgroundColor: '#3b82f6',
    },
    secondaryButton: {
        backgroundColor: '#fff',
        borderWidth: 1,
        borderColor: '#d1d5db',
    },
    disabledButton: {
        opacity: 0.5,
    },
    buttonText: {
        fontSize: 16,
        fontWeight: '600',
    },
    primaryButtonText: {
        color: '#fff',
    },
    secondaryButtonText: {
        color: '#3b82f6',
    },
});

export default Button;