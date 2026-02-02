export default class LetterAvatar {
    constructor(name) {
        this.name = name;
    }

    getAvatar() {
        const nameParts = this.name.split(' ');
        let initials = '';

        // Ambil dua huruf pertama dari nama lengkap
        initials += nameParts[0].charAt(0).toUpperCase();
        if (nameParts.length > 1) {
            initials += nameParts[1].charAt(0).toUpperCase();
        }

        return initials;
    }

    getAvatarWithColor() {
        const initials = this.getAvatar();
        const color = this.getRandomColor();

        return {
            initials: initials,
            backgroundColor: color
        };
    }

    getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
}
