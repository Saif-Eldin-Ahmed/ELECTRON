tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "on-secondary-fixed-variant": "#004f54",
                "on-tertiary-container": "#718e00",
                "primary-fixed": "#e2e2e8",
                "surface-variant": "#e2e2e8",
                "inverse-on-surface": "#f1f0f6",
                "surface-container-lowest": "#ffffff",
                "on-tertiary-fixed-variant": "#3c4d00",
                "surface-tint": "#5d5e63",
                "on-tertiary-fixed": "#161e00",
                "surface-container-low": "#f4f3f9",
                "on-primary-fixed": "#1a1c20",
                "error": "#ba1a1a",
                "on-surface": "#1a1b20",
                "tertiary-fixed": "#c3f400",
                "primary-fixed-dim": "#c6c6cc",
                "surface-container-high": "#e8e7ed",
                "error-container": "#ffdad6",
                "on-background": "#1a1b20",
                "surface": "#f9f9ff",
                "on-secondary-fixed": "#002022",
                "tertiary-fixed-dim": "#abd600",
                "on-primary": "#ffffff",
                "on-error-container": "#93000a",
                "on-secondary-container": "#00686f",
                "tertiary": "#000000",
                "surface-bright": "#f9f9ff",
                "surface-dim": "#dad9df",
                "on-tertiary": "#ffffff",
                "background": "#f9f9ff",
                "surface-container-highest": "#e2e2e8",
                "secondary-container": "#00eefc",
                "on-primary-container": "#828489",
                "primary": "#000000",
                "outline-variant": "#c6c6cb",
                "on-surface-variant": "#45474b",
                "inverse-surface": "#2f3035",
                "secondary-fixed": "#7df4ff",
                "on-secondary": "#ffffff",
                "surface-container": "#eeedf3",
                "secondary": "#006970",
                "inverse-primary": "#c6c6cc",
                "on-error": "#ffffff",
                "outline": "#76777b",
                "primary-container": "#1a1c20",
                "on-primary-fixed-variant": "#45474b",
                "secondary-fixed-dim": "#00dbe9",
                "tertiary-container": "#161e00"
            },
            borderRadius: {
                DEFAULT: "0.25rem",
                lg: "0.5rem",
                xl: "0.75rem",
                full: "9999px"
            },
            spacing: {
                "container-max": "1440px",
                "stack-md": "16px",
                "stack-lg": "32px",
                "section-gap": "120px",
                "gutter": "24px",
                "margin-desktop": "64px",
                "stack-sm": "8px"
            },
            fontFamily: {
                "label-bold": ["Space Grotesk"],
                "body-md": ["Inter"],
                "headline-lg": ["Space Grotesk"],
                "headline-md": ["Space Grotesk"],
                "body-lg": ["Inter"],
                "display-xl": ["Space Grotesk"]
            },
            fontSize: {
                "label-bold": ["14px", {lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "700"}],
                "body-md": ["16px", {lineHeight: "24px", letterSpacing: "0em", fontWeight: "400"}],
                "headline-lg": ["48px", {lineHeight: "52px", letterSpacing: "-0.02em", fontWeight: "600"}],
                "headline-md": ["32px", {lineHeight: "38px", letterSpacing: "-0.01em", fontWeight: "600"}],
                "body-lg": ["18px", {lineHeight: "28px", letterSpacing: "0em", fontWeight: "400"}],
                "display-xl": ["84px", {lineHeight: "90px", letterSpacing: "-0.04em", fontWeight: "700"}]
            }
        }
    }
};
