package uk.ac.ic.doc.g0727127;

import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;

public class FindPath extends HttpServlet {
	private GameMap map = new GameMap();
	private	AStarPathFinder finder = new AStarPathFinder(map);

	public void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException, ServletException {
		response.setContentType("text/xml");
		PrintWriter out = response.getWriter();

		int sx = Integer.parseInt(request.getParameter("sx"));
		int sy = Integer.parseInt(request.getParameter("sy"));
		int tx = Integer.parseInt(request.getParameter("tx"));
		int ty = Integer.parseInt(request.getParameter("ty"));
		String ltr = request.getParameter("ltr");

		findPath(sx, sy, tx, ty, ltr, out);
	}

	public void findPath(int sx, int sy, int tx, int ty, String ltr, PrintWriter out) {
		if ((tx < 0) || (ty < 0) || (tx >= map.getWidth()) || (ty >= map.getHeight())) {
			return;
		}

		Path path = finder.findPath(sx, sy, tx, ty);

		out.print("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n");

		out.print("<steps>\n");
		out.print("<size>" + (path.getLength()-1) + "</size>\n");

		int prev_x = path.getStep(0).getX();

		for(int i=1; i<path.getLength(); i++) {
			out.print("<step>\n");

			int x = path.getStep(i).getX();
			out.print("\t<x>"+x+"</x>\n");
			out.print("\t<y>"+path.getStep(i).getY()+"</y>\n");
			
			if(prev_x > x) { // Moving right-to-left
				ltr = "false";
			}
			else if (prev_x < x) { // Moving left-to-right
				ltr = "true";
			}

			out.print("\t<ltr>"+ltr+"</ltr>\n");

			out.print("</step>\n");
			prev_x = x;
		}

		out.print("</steps>\n");
	}
}