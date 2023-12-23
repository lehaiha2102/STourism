export const EstimatedReadingTime = (content) => {
    if (typeof content !== 'string') {
        content = content.toString();
    }
    var charCount = content.split(/\s+/).length;
    
    const timeReading = Math.ceil(charCount / 100);
    return timeReading;
}