export const formatDate = (date: Date, format: string) => {
    if (!(date instanceof Date) && typeof date !== 'string') {
        return '';
    }

    if (typeof date === 'string') {
        date = new Date(date);
    }

    return format.replace(/Y|m|d|H|i|s/g, (match) => {
        switch (match) {
            case 'Y':
                return date.getFullYear();
            case 'm':
                return String(date.getMonth() + 1).padStart(2, '0');
            case 'd':
                return String(date.getDate()).padStart(2, '0');
            case 'H':
                return String(date.getHours()).padStart(2, '0');
            case 'i':
                return String(date.getMinutes()).padStart(2, '0');
            case 's':
                return String(date.getSeconds()).padStart(2, '0');
            default:
                return match;
        }
    });
}
