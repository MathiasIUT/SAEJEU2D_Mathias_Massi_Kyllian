package universite_paris8.iut.mcontay.saejeu2d.modele;

public class PersoQuiAttaque extends Entite {
    private int ptsAttaque;
    private int ptsDefense;

    public PersoQuiAttaque(Environnement environnement, Terrain terrain, String nom, int ptsDeVie, int ptsAttaque, int ptsDefense, double initialX, double initialY, int id, int moveDistance) {
        super(environnement, terrain, nom, ptsDeVie, initialX, initialY, id, moveDistance);
        this.ptsAttaque = ptsAttaque;
        this.ptsDefense = ptsDefense;
    }

    private int calculerDegats(Objet arme) {
        switch (arme.getNom()) {
            case "Épée":
                return 20;
            case "Bouclier":
                return 5;
            default:
                return 1;
        }
    }

    private void infligerDegats(int degats) {
        int vieActuelle = getEnvironnement().getJoueur().getPtsDeVie();
        int nouvelleVie = vieActuelle - degats;
        getEnvironnement().getJoueur().setPtsDeVie(nouvelleVie);
        System.out.println("Dégâts infligés : " + degats + ", Vie restante : " + nouvelleVie);
    }



    public int getPtsAttaque() {
        return ptsAttaque;
    }

    public int getPtsDefense() {
        return ptsDefense;
    }
}
