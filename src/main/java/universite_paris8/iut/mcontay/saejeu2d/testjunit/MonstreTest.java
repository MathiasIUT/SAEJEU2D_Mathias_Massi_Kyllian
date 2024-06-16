//package universite_paris8.iut.mcontay.saejeu2d.testjunit;
//
//import static org.junit.jupiter.api.Assertions.*;
//import org.junit.jupiter.api.BeforeEach;
//import org.junit.jupiter.api.Test;
//import universite_paris8.iut.mcontay.saejeu2d.modele.*;
//
//public class MonstreTest {
//    private Environnement environnement;
//    private Terrain terrain;
//    private Monstre monstre;
//    private Joueur joueur;
//
//    @BeforeEach
//    public void setUp() {
//        terrain = new Terrain("/universite_paris8/iut/mcontay/saejeu2d/jsonmapsurfacefinal.json");
//        environnement = new Environnement();
//        joueur = new Joueur(environnement, terrain, "Hector", 100, 20, 10, 0, 0, 1, 3);
//        monstre = new Monstre(environnement, terrain, "Vilain Monstre", 100, 25, 50, 1, 1, 2, 1);
//    }
//
//    @Test
//    public void testDeplacer() {
//        monstre.setPositionX(0);
//        monstre.setPositionY(0);
//        monstre.deplacer();
//        assertNotEquals(0, monstre.getPositionX());
//        assertNotEquals(0, monstre.getPositionY());
//    }
//
//
//    @Test
//    public void testEnleverPV() {
//        monstre.enleverPV(20);
//        assertEquals(80, monstre.getPtsDeVie());
//        monstre.enleverPV(80);
//        assertTrue(monstre.estMortProperty().get());
//    }
//}
