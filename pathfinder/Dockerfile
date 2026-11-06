FROM gradle:9.8.2-jdk25 AS build
COPY --chown=gradle:gradle . /home/gradle/src
WORKDIR /home/gradle/src
RUN gradle build --no-daemon

FROM amazoncorretto:25-alpine3.24

EXPOSE 8090

RUN mkdir /app

COPY --from=build /home/gradle/src/app/build/libs/*.jar /app/pathfinder.jar

ENTRYPOINT ["java","-Djava.security.egd=file:/dev/./urandom","-jar","/app/pathfinder.jar"]