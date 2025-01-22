document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const fileInput = document.getElementById('fichier');
    const maxSize = 50 * 1024 * 1024; // 50MB
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'text/plain'];
    
    form?.addEventListener('submit', function(e) {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            
            // Vérification de la taille
            if (file.size > maxSize) {
                e.preventDefault();
                alert('Le fichier est trop volumineux. La taille maximale est de 50MB.');
                return;
            }
            
            // Vérification du type
            if (!allowedTypes.includes(file.type)) {
                e.preventDefault();
                alert('Type de fichier non autorisé. Les formats acceptés sont: PDF, DOC, DOCX, ZIP, TXT');
                return;
            }
        }
    });
    
    // Prévisualisation du nom du fichier
    fileInput?.addEventListener('change', function() {
        const fileName = this.files[0]?.name;
        const fileInfo = document.createElement('div');
        fileInfo.className = 'mt-2 text-muted';
        fileInfo.textContent = `Fichier sélectionné: ${fileName}`;
        
        const existingInfo = this.parentElement.querySelector('.text-muted:not(small)');
        if (existingInfo) {
            existingInfo.remove();
        }
        
        this.parentElement.appendChild(fileInfo);
    });
});
