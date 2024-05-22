package universite_paris8.iut.mcontay.saejeu2d.modele;

import universite_paris8.iut.mcontay.saejeu2d.controleur.Controleur;

import static universite_paris8.iut.mcontay.saejeu2d.modele.Joueur.MOVE_DISTANCE;

public class Terrain {
    //                       1  2  3  4  5  6  7  8  9  10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31 32
    private int[][] codesTuiles = {{11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 03, 03, 03, 03, 03, 11, 11, 11, 11},  /* 1 */
            /* 2 */        {11, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 06, 06, 06, 11},
            /* 3 */        {11, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 4 */        {11, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 5 */        {11, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 6 */        {11, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 7 */        {11, 06, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 8 */        {11, 06, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 06, 06, 06, 11},
            /* 9 */        {11, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 06, 06, 06, 11},
            /* 10 */       {11, 06, 06, 06, 02, 02, 02, 02, 06, 06, 06, 06, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 01, 03, 01, 03, 01, 03, 01, 07, 07, 11},
            /* 11 */       {11, 06, 02, 02, 02, 04, 04, 02, 02, 06, 06, 06, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 01, 01, 01, 01, 01, 01, 01, 07, 07, 11},
            /* 12 */       {11, 06, 02, 04, 04, 04, 04, 02, 02, 06, 06, 06, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 01, 01, 01, 01, 01, 01, 01, 07, 07, 11},
            /* 13 */       {11, 06, 02, 02, 04, 02, 02, 02, 06, 06, 06, 06, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 01, 03, 01, 03, 01, 03, 01, 07, 07, 11},
            /* 14 */       {11, 06, 06, 02, 02, 02, 06, 06, 06, 06, 06, 06, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 15 */       {11, 06, 06, 06, 06, 06, 06, 07, 07, 07, 07, 07, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 16 */       {11, 06, 06, 06, 06, 06, 06, 07, 07, 07, 07, 07, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 17 */       {11, 06, 06, 06, 06, 06, 06, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 07, 07, 07, 07, 07, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 18 */       {11, 06, 06, 06, 06, 06, 06, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 07, 07, 07, 07, 07, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 19 */       {11, 06, 06, 06, 06, 06, 06, 07, 07, 06, 8, 8, 8, 8, 8, 06, 06, 06, 07, 07, 07, 07, 07, 06, 06, 03, 03, 03, 03, 03, 06, 11},
            /* 20 */       {11, 06, 06, 06, 06, 06, 06, 07, 07, 06, 8, 8, 8, 8, 8, 06, 06, 06, 07, 07, 07, 07, 07, 06, 06, 03, 03, 03, 03, 03, 06, 11},
            /* 21 */       {11, 07, 07, 07, 07, 07, 07, 07, 07, 06, 8, 8, 8, 8, 8, 06, 06, 06, 07, 07, 07, 07, 07, 06, 06, 03, 03, 03, 03, 03, 06, 11},
            /* 22 */       {11, 07, 07, 07, 07, 07, 07, 07, 07, 06, 8, 8, 8, 8, 8, 06, 06, 06, 07, 07, 07, 07, 07, 06, 06, 03, 03, 03, 03, 03, 06, 11},
            /* 23 */       {11, 10, 10, 10, 10, 10, 10, 07, 07, 06, 8, 8, 8, 8, 8, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 06, 11},
            /* 24 */       {11, 10, 10, 10, 10, 10, 10, 07, 07, 06, 8, 8, 8, 8, 8, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 06, 11},
            /* 25 */       {11, 10, 10, 10, 10, 10, 10, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 03, 03, 03, 03, 03, 03, 06, 11},
            /* 26 */       {11, 10, 10, 10, 10, 10, 10, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 06, 06, 03, 03, 03, 03, 03, 03, 06, 11},
            /* 27 */       {11, 10, 10, 10, 10, 10, 10, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 07, 06, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 28 */       {11, 10, 10, 10, 10, 10, 10, 07, 07, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 06, 07, 07, 06, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 29 */       {11, 10, 10, 10, 10, 10, 10, 07, 07, 06, 9, 9, 9, 9, 9, 9, 9, 9, 9, 06, 07, 07, 06, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 30 */       {11, 10, 10, 10, 10, 10, 10, 07, 07, 06, 9, 9, 9, 9, 9, 9, 9, 9, 9, 06, 07, 07, 06, 06, 03, 03, 03, 03, 03, 06, 06, 11},
            /* 31 */       {11, 10, 10, 10, 10, 10, 10, 07, 07, 06, 9, 9, 9, 9, 9, 9, 9, 9, 9, 06, 07, 07, 06, 06, 03, 03, 03, 03, 03, 03, 06, 11},
            /* 32 */       {11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 9, 9, 9, 9, 9, 9, 9, 9, 9, 11, 11, 11, 11, 11, 03, 03, 03, 03, 03, 03, 11, 11}};


    private Joueur joueur;



    private Terrain terrain;

    public Terrain() {
        this.joueur = new Joueur(terrain, 0, 0);


    }

    public int[][] getCodesTuiles() {
        return codesTuiles;
    }

    public int getLongueur() {
        return codesTuiles[0].length;
    }

    public int getHauteur() {
        return codesTuiles.length;
    }

    public boolean estAutorisee(double x, double y) {


        if (codesTuiles[(int)((y)/16)][(int)((x)/16)] == 3) {
            System.out.println("impossible");
            return false;
        }

        else if (x >= 0 && x < getLongueur() * 32 && y >= 0 && y < getHauteur() * 32) {

            return true;
        }
        return false;

    }
}