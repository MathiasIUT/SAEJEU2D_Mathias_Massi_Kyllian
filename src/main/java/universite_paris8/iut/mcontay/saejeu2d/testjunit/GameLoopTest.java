//import static org.junit.jupiter.api.Assertions.*;
//import org.junit.jupiter.api.BeforeEach;
//import org.junit.jupiter.api.Test;
//import javafx.animation.KeyFrame;
//import javafx.animation.Timeline;
//import javafx.util.Duration;
//
//public class GameLoopTest {
//    private Environnement environnement;
//    private GameLoop gameLoop;
//    private boolean updateCalled;
//
//    @BeforeEach
//    public void setUp() {
//        environnement = new Environnement();
//        updateCalled = false;
//        gameLoop = new GameLoop(environnement, () -> updateCalled = true);
//    }
//
//    @Test
//    public void testGameLoopStopWhenPlayerDies() {
//        joueur.setPtsDeVie(0);
//        gameLoop.initGameLoop();
//        Timeline gameLoopTimeline = (Timeline) gameLoop.getClass().getDeclaredField("gameLoop").get(gameLoop);
//        assertFalse(gameLoopTimeline.getStatus() == Timeline.Status.RUNNING);
//    }
//}
