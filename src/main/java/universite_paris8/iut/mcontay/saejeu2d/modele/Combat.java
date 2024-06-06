package universite_paris8.iut.mcontay.saejeu2d.modele;

public class Combat {
    private Joueur joueur;
    private Inventaire inventaire;
    public Combat(Joueur joueur, Inventaire inventaire) {//TODO ajouter l'ennemi
        this.joueur = joueur;
        this.inventaire = inventaire;
    }
    public void attaquer(Objet arme) {
        if (Inventaire.contientObjet(arme)) {
            int degats = calculerDegats(arme);
            infligerDegats(degats);
        } else {
            System.out.println("L'arme sélectionnée n'est pas dans l'inventaire.");
        }
    }
    private int calculerDegats(Objet arme) {
        switch (arme.getNom()) {
            case "Épée":
                return 100;
            case "Bouclier":
                return 5;
            default:
                return 1;
        }
    }
    private void infligerDegats(int degats) {
        int vieActuelle = joueur.getVie();
        int nouvelleVie = vieActuelle - degats;
        joueur.setVie(nouvelleVie);//TODO changer joueur par ennemi
        System.out.println("Dégâts infligés : " + degats + ", Vie restante : " + nouvelleVie);
    }
    public Inventaire getInventaire() {
        return inventaire;
    }
    public Joueur getJoueur() {
        return joueur;
    }
}
