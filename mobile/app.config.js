export default {
    expo: {
        name: "EnterpriseERP Mobile",
        slug: "enterpriseerp-mobile",
        version: "1.0.0",
        orientation: "portrait",
        icon: "./assets/icon.png",
        userInterfaceStyle: "light",
        splash: {
            image: "./assets/splash-icon.png",
            resizeMode: "contain",
            backgroundColor: "#ffffff"
        },
        assetBundlePatterns: [
            "**/*"
        ],
        ios: {
            supportsTablet: true,
            bundleIdentifier: "com.enterpriseerp.mobile"
        },
        android: {
            adaptiveIcon: {
                backgroundColor: "#ffffff",
                foregroundImage: "./assets/android-icon-foreground.png",
                backgroundImage: "./assets/android-icon-background.png",
                monochromeImage: "./assets/android-icon-monochrome.png"
            },
            package: "com.enterpriseerp.mobile"
        },
        web: {
            favicon: "./assets/favicon.png"
        },
        extra: {
            apiUrl: process.env.EXPO_PUBLIC_API_URL || "http://192.168.1.100:8000/api",
            apiTimeout: parseInt(process.env.EXPO_PUBLIC_API_TIMEOUT || "10000", 10),
            environment: process.env.EXPO_PUBLIC_ENV || "development",
        }
    }
};