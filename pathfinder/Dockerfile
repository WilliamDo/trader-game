FROM gradle:9.8.2-jdk25 AS build
COPY --chown=gradle:gradle . /home/gradle/src
WORKDIR /home/gradle/src
RUN gradle build --no-daemon

FROM eclipse-temurin:25

EXPOSE 8090

RUN mkdir /app

COPY --from=build /home/gradle/src/app/build/libs/*.jar /app/pathfinder.jar

CMD ["java", "-jar", "/app/pathfinder.jar"]