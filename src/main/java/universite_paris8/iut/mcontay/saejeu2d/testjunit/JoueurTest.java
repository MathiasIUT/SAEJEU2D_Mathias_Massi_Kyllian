//import static org.junit.jupiter.api.Assertions.*;
//import org.junit.jupiter.api.BeforeEach;
//import org.junit.jupiter.api.Test;
//
//public class JoueurTest {
//    private Environnement environnement;
//    private Terrain terrain;
//    private Joueur joueur;
//    private Monstre monstre;
//
//    @BeforeEach
//    public void setUp() {
//        terrain = new Terrain("/path/to/terrain.json");
//        environnement = new Environnement();
//        joueur = new Joueur(environnement, terrain, "Hector", 100, 20, 10, 0, 0, 1, 3);
//        monstre = new Monstre(environnement, terrain, "Vilain Monstre", 100, 25, 50, 1, 1, 2, 1);
//    }
//
//    @Test
//    public void testDeplacer() {
//        joueur.setPositionX(0);
//        joueur.setPositionY(0);
//        joueur.deplacementDroite();
//        assertEquals(3, joueur.getPositionX());
//        joueur.deplacementBas();
//        assertEquals(3, joueur.getPositionY());
//    }
//
//    @Test
//    public void testAttaquer() {
//        joueur.attaquer(monstre);
//        assertEquals(90, monstre.getPtsDeVie()); // Assuming 10 damage per attack
//    }
//
//    @Test
//    public void testAjouterPtsDeVie() {
//        joueur.ajouterPtsDeVie(20);
//        assertEquals(120, joueur.getPtsDeVie());
//    }
//
//    @Test
//    public void testEstProche() {
//        monstre.setPositionX(10);
//        monstre.setPositionY(10);
//        joueur.setPositionX(0);
//        joueur.setPositionY(0);
//        assertTrue(joueur.estProche(monstre));
//    }
//}