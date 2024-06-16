package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.BooleanProperty;
import javafx.beans.property.SimpleBooleanProperty;

import java.util.LinkedList;
import java.util.Random;

/* La classe Monstre étend PersoQuiAttaque et représente un monstre dans le jeu avec des capacités de déplacement et d'attaque.
 Elle utilise un compteur de déplacement pour suivre les mouvements à intervalles réguliers et suit le joueur pour l'attaquer en utilisant un algorithme de recherche de chemin (BFS).
  Si le joueur est à portée d'attaque, le monstre inflige des dégâts et, sinon, il se déplace de manière aléatoire.
 Les méthodes permettent de gérer les déplacements, les attaques, et de mettre à jour l'état de mort du monstre.*/

public class Monstre extends PersoQuiAttaque {

    private int deplacementCounter = 0;
    private static final int DEPLACEMENT_INTERVAL = 6;
    private BFS bfs;
    private BooleanProperty estMort;

    public Monstre(Environnement environnement, Terrain terrain, String nom, int ptsDeVie, int ptsAttaque, int ptsDefense, double initialX, double initialY, int id, int moveDistance) {
        super(environnement, terrain, nom, ptsDeVie, ptsAttaque, ptsDefense, initialX, initialY, id, moveDistance);
        this.bfs = new BFS(terrain);
        this.estMort = new SimpleBooleanProperty(false);
    }

    @Override
    public void deplacer() {
        super.deplacer();
        deplacementCounter++;
        if (deplacementCounter >= DEPLACEMENT_INTERVAL) {
            suivreEtAttaquerJoueur();
            deplacementCounter = 0;
        }
    }

    public void setEstMort(boolean estMort) {
        this.estMort.set(estMort);
    }

    public void suivreEtAttaquerJoueur() {
        Joueur joueur = getEnvironnement().getJoueur();
        if (joueur == null) {
            return;
        }

        double distanceX = Math.abs(joueur.getPositionX() - getPositionX());
        double distanceY = Math.abs(joueur.getPositionY() - getPositionY());
        double distance = Math.sqrt(distanceX * distanceX + distanceY * distanceY);

        if (distance <= 100) {  // Si le joueur est à portée
            LinkedList<int[]> path = bfs.findPath((int) getPositionX() / 16, (int) getPositionY() / 16, (int) joueur.getPositionX() / 16, (int) joueur.getPositionY() / 16);
            if (!path.isEmpty() && path.size() > 1) {
                int[] prochainMouvement = path.get(1); // Le premier mouvement du chemin
                seDeplacerVers(prochainMouvement[0] - (int) getPositionX() / 16, prochainMouvement[1] - (int) getPositionY() / 16);
            }
            if (distance <= 16) {  // Distance d'attaque
                enleveDegats(joueur);
                if (joueur.getPtsDeVie() <= 0) {
                    joueur.setEstMort(true);
                }
            } else {
                monstreSeDeplaceAleatoire(joueur);
            }
        }
    }

    private void seDeplacerVers(int dx, int dy) {
        if (dx == -1) {
            seDeplaceGauche();
        } else if (dx == 1) {
            seDeplaceDroite();
        } else if (dy == -1) {
            seDeplaceHaut();
        } else if (dy == 1) {
            seDeplaceBas();
        }
    }

    public void monstreSeDeplaceAleatoire(Joueur joueur) {
        Random random = new Random();

        double joueurX = joueur.getPositionX();
        double joueurY = joueur.getPositionY();
        double monstreX = getPositionX();
        double monstreY = getPositionY();

        double dx = joueurX - monstreX;
        double dy = joueurY - monstreY;

        int directionX = (dx > 0) ? 1 : -1;
        int directionY = (dy > 0) ? 1 : -1;

        int directionAleatoire = random.nextInt(2) + 1;
        if (Math.abs(dx) > Math.abs(dy)) {
            if (directionAleatoire == 1) {
                seDeplacerVers(directionX, 0);
            } else {
                seDeplacerVers(0, directionY);
            }
        } else {
            if (directionAleatoire == 1) {
                seDeplacerVers(0, directionY);
            } else {
                seDeplacerVers(directionX, 0);
            }
        }
    }

    public void enleverPV(int degats) {
        setPtsDeVie(getPtsDeVie() - degats);
        if (getPtsDeVie() <= 0) {
            setEstMort(true);
        }
        System.out.println("Monstre a perdu " + degats + " points de vie. Il lui reste " + getPtsDeVie() + " points de vie.");
    }

    public void enleveDegats(Joueur joueur) {
        joueur.enleverPV(5);
    }

    public BooleanProperty estMortProperty() {
        return estMort;
    }
}
