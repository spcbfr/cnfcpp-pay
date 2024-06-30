import preset from "./vendor/filament/support/tailwind.config.preset";

export default {
    presets: [preset],
    theme: {
        fontFamily: {
            sans: ["Inter"],
        },
    },
    content: [
        "./app/Filament/**/*.php",
        "./resources/views/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],
};
