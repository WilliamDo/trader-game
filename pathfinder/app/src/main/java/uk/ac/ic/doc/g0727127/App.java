/*
 * This Java source file was generated by the Gradle 'init' task.
 */
package uk.ac.ic.doc.g0727127;

import org.eclipse.jetty.server.Server;
import org.eclipse.jetty.servlet.ServletHandler;

public class App {

    public static void main(String[] args) {
        Server server = new Server(8090);
        try {
            // The ServletHandler is a dead simple way to create a context handler
            // that is backed by an instance of a Servlet.
            // This handler then needs to be registered with the Server object.
            ServletHandler handler = new ServletHandler();
            server.setHandler(handler);

            // Passing in the class for the Servlet allows jetty to instantiate an
            // instance of that Servlet and mount it on a given context path.

            // IMPORTANT:
            // This is a raw Servlet, not a Servlet that has been configured
            // through a web.xml @WebServlet annotation, or anything similar.
            handler.addServletWithMapping(FindPath.class, "/servlet/FindPath");
            server.start();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
